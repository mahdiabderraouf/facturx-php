<?php

namespace MahdiAbderraouf\FacturX\Fpdi;

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfType;

/**
 * This class is an enhanced version of the original work by the authors of https://github.com/atgp/factur-x, and Olivier from http://www.fpdf.org/.
 *
 * Credit to the original authors for their foundational work.
 *
 * @see http://www.fpdf.org/en/script/script95.php
 * @see http://www.fpdf.org/en/script/script103.php
 */
class PdfA3b extends Fpdi
{
    private readonly string $pdfPath;
    private array $attachments = [];
    private string $xmp;
    private string $pdfId;
    private int $xmpIndex = 0;
    private int $outputIntentIndex = 0;
    private int $nFiles = 0;

    public function __construct(string $pdfPath, $orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);

        $this->pdfPath = $pdfPath;
        $this->setMinPdfVersion('1.7');
        $this->copyPages();
    }

    /**
     * Specification : ISO 19005-1:2005
     */
    public function setPdfId(string $createDate, string $updateDate): void
    {
        $this->pdfId = '[<' . bin2hex($createDate) . '><' . bin2hex($updateDate) . '>]';
    }

    public function setAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->attachments[] = [
                'file' => $attachment['file'],
                'filename' => mb_convert_encoding($attachment['filename'], 'UTF-8'),
                'description' => mb_convert_encoding($attachment['description'], 'UTF-8'),
                'relationship' => $attachment['relationship'] ?? AttachmentRelationship::UNSPECIFIED->value,
                'mimeType' => str_replace('/', '#2F', mime_content_type($attachment['file'])),
            ];
        }
    }

    public function setXmp(string $xmp): void
    {
        $this->xmp = $xmp;
    }

    /**
     * Set the PDF version.
     */
    #[\Override]
    protected function _putheader()
    {
        parent::_putheader();

        $this->_put($this->getVersionBinaryComment());
    }

    /**
     * Put resources including files and metadata descriptions.
     *
     * @throws \Exception
     */
    #[\Override]
    protected function _putresources()
    {
        parent::_putresources();
        if ($this->attachments) {
            $this->putAttachments();
        }
        $this->putOutputIntent();
        $this->putXmp();
    }

    /**
     * Put catalog node, including associated files.
     */
    #[\Override]
    protected function _putcatalog()
    {
        parent::_putcatalog();

        if (count($this->attachments)) {
            $attachmentsRef = '';
            foreach ($this->attachments as $attachment) {
                if ($attachmentsRef) {
                    $attachmentsRef .= ' ';
                }
                $attachmentsRef .= $attachment['fileIndex'] . ' 0 R';
            }
            $this->_put('/AF [' . $attachmentsRef . ']');
        } else {
            $this->_put('/AF ' . $this->nFiles . ' 0 R');
        }

        if ($this->xmpIndex) {
            $this->_put('/Metadata ' . $this->xmpIndex . ' 0 R');
        }
        $this->_put('/Names <<');
        $this->_put('/EmbeddedFiles ');
        $this->_put($this->nFiles . ' 0 R');
        $this->_put('>>');

        if ($this->outputIntentIndex) {
            $this->_put('/OutputIntents [' . $this->outputIntentIndex . ' 0 R]',);
        }

        /**
         * Force the PDF viewer to open the attachment pane when the document is loaded
         * This feature is not supported by all PDF readers
         */
        $this->_put('/PageMode /UseAttachments');
    }

    /**
     * Put trailer including ID.
     */
    #[\Override]
    protected function _puttrailer()
    {
        parent::_puttrailer();

        $this->_put('/ID ' . $this->pdfId);
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function writePdfType(PdfType $value)
    {
        parent::writePdfType($value);

        if ($value instanceof PdfIndirectObject && \PHP_EOL !== substr((string) $this->buffer, -1)) {
            $this->_put('');
        }
    }

    private function copyPages(): void
    {
        $pageCount = $this->setSourceFile($this->pdfPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $this->importPage($i, '/MediaBox', importExternalLinks: true);
            $this->AddPage();
            $this->useTemplate($templateId, adjustPageSize: true);
        }
    }

    /**
     * Specification : ISO 19005-1:2005
     */
    private function getVersionBinaryComment(): string
    {
        return '%' .
            chr(mt_rand(128, 256)) .
            chr(mt_rand(128, 256)) .
            chr(mt_rand(128, 256)) .
            chr(mt_rand(128, 256));
    }

    private function putAttachments(): void
    {
        $attachmentsCount = count($this->attachments);

        for ($i = 0; $i < $attachmentsCount; $i++) {
            $this->putAttachmentSpecification($this->attachments[$i]);
            $this->attachments[$i]['fileIndex'] = $this->n;
            $this->putAttachment($this->attachments[$i]);
        }

        $this->putFileDictionary();
    }

    /**
     * Put file attachment specification.
     */
    private function putAttachmentSpecification(array $attachment): void
    {
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/F (' . $this->_escape($attachment['filename']) . ')');
        $this->_put('/Type /Filespec');
        $this->_put('/UF ' . $this->_textstring($attachment['filename']));
        $this->_put('/AFRelationship /' . $attachment['relationship']);
        if ($attachment['description']) {
            $this->_put('/Desc ' . $this->_textstring($attachment['description']));
        }
        $this->_put('/EF <<');
        $this->_put('/F ' . ($this->n + 1) . ' 0 R');
        $this->_put('/UF ' . ($this->n + 1) . ' 0 R');
        $this->_put('>>');
        $this->_put('>>');
        $this->_put('endobj');
    }

    private function putAttachment(array $attachment): void
    {
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Filter /FlateDecode');
        $this->_put('/Subtype /' . $attachment['mimeType']);
        $this->_put('/Type /EmbeddedFile');

        $fileContent = gzcompress(file_get_contents($attachment['file']));
        $updateAt = @date('YmdHis', filemtime($attachment['file']));

        $this->_put('/Length ' . strlen($fileContent));
        $this->_put("/Params <</ModDate (D:$updateAt)>>");
        $this->_put('>>');
        $this->_putstream($fileContent);
        $this->_put('endobj');
    }

    private function putFileDictionary(): void
    {
        $this->_newobj();
        $this->nFiles = $this->n;
        $this->_put('<<');
        $this->_put('/Names [' . $this->getSortedAttachmentNames() . ']');
        $this->_put('>>');
        $this->_put('endobj');
    }

    /**
     * Sorting attachments in name order as PDF specifications
     */
    private function getSortedAttachmentNames(): string
    {
        $attachmentsNamesSorted = '';
        $attachments = $this->attachments;
        usort($attachments, function ($a, $b) {
            return strcmp((string) $a['filename'], (string) $b['filename']);
        });

        foreach ($attachments as $attachment) {
            $attachmentsNamesSorted .= $this->_textstring($attachment['filename']) .
                ' ' . $attachment['fileIndex'] .
                ' 0 R ';
        }

        return $attachmentsNamesSorted;
    }

    private function putXmp(): void
    {
        $this->_newobj();
        $this->xmpIndex = $this->n;
        $this->_put('<<');
        $this->_put('/Length ' . strlen($this->xmp));
        $this->_put('/Type /Metadata');
        $this->_put('/Subtype /XML');
        $this->_put('>>');
        $this->_putstream($this->xmp);
        $this->_put('endobj');
    }

    /**
     * Put output intent with ICC profile.
     */
    private function putOutputIntent(): void
    {
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Type /OutputIntent');
        $this->_put('/S /GTS_PDFA1');
        $this->_put('/OuputCondition (sRGB)');
        $this->_put('/OutputConditionIdentifier (Custom)');
        $this->_put('/DestOutputProfile ' . ($this->n + 1) . ' 0 R');
        $this->_put('/Info (sRGB V4 ICC)');
        $this->_put('>>');
        $this->_put('endobj');

        $this->outputIntentIndex = $this->n;

        $icc = file_get_contents(__DIR__ . '/../../resources/icc/sRGB2014.icc');

        $this->_newobj();
        $this->_put('<<');
        $this->_put('/Length ' . strlen($icc));
        $this->_put('/N 3');
        $this->_put('/Filter /FlateDecode');
        $this->_put('>>');
        $this->_putstream($icc);
        $this->_put('endobj');
    }
}
