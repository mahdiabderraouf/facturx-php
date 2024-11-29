<?php

namespace MahdiAbderraouf\FacturX;

use DateTime;
use DOMXPath;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\FacturXProfile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Helpers\PdfWithAttachments;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Helpers\Version;
use MahdiAbderraouf\FacturX\Helpers\XmlExtractor;
use setasign\Fpdi\Fpdi;

class FacturXGenerator
{
    /**
     * Generate Factur-X PDF
     *
     * @throws InvalidArgumentException
     * @throws NotPdfFileException
     */
    public static function generate(
        string $pdfPath,
        string $xml,
        ?FacturXProfile $profile = null,
        AttachmentRelationship $relationship = AttachmentRelationship::DATA
    ): string {
        if (!Utils::isPdfFile($pdfPath)) {
            throw new NotPdfFileException('The file ' . $pdfPath . ' is not a PDF file');
        }

        if (!$relationship->isAllowedForFacturxXml()) {
            throw new InvalidArgumentException('Invalid argument $relationship: ' . $relationship->value);
        }

        $xml = self::resolveXml($xml);

        $profile ??=

        FacturXValidator::validate($xml, $profile);

        $pdfWithAttachments = new PdfWithAttachments($pdfPath);

        $invoiceData = FacturXParser::extractBaseData($xml);
        // Update date is the current date
        // Create date is the invoice issue date
        $updateDate = (new DateTime())->setTime(0, 0);

        $pdfWithAttachments->setAttachments(self::buildAttachmentsArray($xml, $relationship));
        $pdfWithAttachments->setPdfId('2024/11/29', '2024/11/29');
        $pdfWithAttachments->setXmp(self::buildXmpString($invoiceData, $updateDate, $profile));

        // Set XMP metadata

        return $pdfWithAttachments->Output('S');
    }

    private static function resolveXml(string $xml): string
    {
        if (Utils::isXmlFile($xml)) {
            return $xml;
        }

        $tmpFile = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];
        file_put_contents($tmpFilePath, $xml);

        return $tmpFilePath;
    }

    private function buildXmpString(array $invoiceData, DateTime $updateDate, FacturXProfile $profile): string
    {
        $xmp = file_get_contents(__DIR__ . '/../resources/xmp/FACTUR-X_extension_schema.xmp');

        $xmp = str_replace('{documentNumber}', $invoiceData['documentNumber'], $xmp);
        $xmp = str_replace('{supplier}', $invoiceData['supplier'], $xmp);
        $xmp = str_replace('{issueDate}', $invoiceData['issueDate']->format('Y-m-d'), $xmp);
        $xmp = str_replace('{documentCreateDate}', $invoiceData['issueDate']->format('Y-m-d'), $xmp);
        $xmp = str_replace('{documentUpdateDate}', $updateDate->format('Y-m-d'), $xmp);
        $xmp = str_replace('{facturxFilename}', XmlFilename::FACTUR_X->value, $xmp);
        $xmp = str_replace('{facturxVersion}', Version::FACTURX_VERSION, $xmp);
        $xmp = str_replace('{facturxProfile}', $profile->value, $xmp);

        return $xmp;
    }

    private function buildAttachmentsArray(string $xml, AttachmentRelationship $relationship, array $additionalAttachments = []): array
    {
        $attachments = [
            [
                'file' => $xml,
                'filename' => XmlFilename::FACTUR_X,
                'relationship' => $relationship,
                'description' => 'Factur-X Invoice',
            ],
        ];

        foreach ($additionalAttachments as $additionalAttachment) {
            $attachments[] = [
                'file' => $additionalAttachment['file'],
                'file' => $additionalAttachment['filename'] ?? basename($additionalAttachment['filename']),
                'relationship' => $additionalAttachment['relationship'] ?? AttachmentRelationship::UNSPECIFIED,
                'description' => $additionalAttachment['description'] ?? '',
            ];
        }

        return $attachments;
    }
}
