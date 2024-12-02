<?php

namespace MahdiAbderraouf\FacturX;

use DateTime;
use Exception;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Fpdi\PdfA3b;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Helpers\Version;
use MahdiAbderraouf\FacturX\Models\Invoice;

class Generator
{
    /**
     * Generate Factur-X PDF
     *
     * @param array<array> $attachments additional attachments:
     *      - string 'file': The file path.
     *      - ?string 'filename': Alternative file name defaults to given file basename.
     *      - ?string 'relationship': Enum value of AttachmentRelationship.
     *      - ?string 'description': A description of the attachment.
     *
     * @throws InvalidArgumentException
     * @throws NotPdfFileException
     * @throws InvalidXmlException
     * @throws Exception
     */
    public static function generate(
        string $pdfPath,
        string|Invoice $xml,
        AttachmentRelationship $relationship = AttachmentRelationship::DATA,
        ?string $outputPath = null,
        ?Profile $profile = null,
        ?array $additionalAttachments = []
    ): string {
        if (!Utils::isPdfFile($pdfPath)) {
            throw new NotPdfFileException('The file ' . $pdfPath . ' is not a PDF file');
        }

        if (!$relationship->isAllowedForFacturxXml()) {
            throw new InvalidArgumentException('Invalid argument $relationship: ' . $relationship->value);
        }

        $xml = self::resolveXml($xml);

        $profile ??= Parser::getProfile($xml);

        if (in_array($profile, [Profile::MINIMUM, Profile::BASIC_WL]) && $relationship !== AttachmentRelationship::DATA) {
            throw new InvalidArgumentException('Invalid argument $relationship: ' . $relationship->value . ', only the relationship Data is allowed for minimumn and basic-wl profiles.');
        }

        Validator::validate($xml, $profile);

        // Update date is the current date, while create date is the invoice issue date
        $updateDate = (new DateTime())->setTime(0, 0);
        $invoiceData = Parser::extractBaseData($xml);

        $pdf = new PdfA3b($pdfPath);

        $pdf->setAttachments(self::buildAttachmentsArray($xml, $relationship, $additionalAttachments));
        $pdf->setPdfId($invoiceData['issueDate']->format('Y-m-d'), $updateDate->format('Y-m-d'));
        $pdf->setXmp(self::buildXmpString($invoiceData, $updateDate, $profile));

        if ($outputPath) {
            return $pdf->Output('F', $outputPath);
        }

        return $pdf->Output('S');
    }

    private static function resolveXml(string|Invoice $xml): string
    {
        if ($xml instanceof Invoice) {
            $xml = $xml->toXml();
        }

        if (Utils::isXmlFile($xml)) {
            return $xml;
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'FACTURX_XML');
        file_put_contents($tmpFile, $xml);

        return $tmpFile;
    }

    private static function buildXmpString(array $invoiceData, DateTime $updateDate, Profile $profile): string
    {
        $xmp = file_get_contents(__DIR__ . '/../resources/xmp/FACTUR-X_extension_schema.xmp');

        $xmp = str_replace('{documentNumber}', $invoiceData['documentNumber'], $xmp);
        $xmp = str_replace('{supplier}', $invoiceData['supplier'], $xmp);
        $xmp = str_replace('{issueDate}', $invoiceData['issueDate']->format('Y-m-d'), $xmp);
        $xmp = str_replace('{documentCreateDate}', $invoiceData['issueDate']->format('Y-m-d'), $xmp);
        $xmp = str_replace('{documentUpdateDate}', $updateDate->format('Y-m-d'), $xmp);
        $xmp = str_replace('{facturxFilename}', XmlFilename::FACTUR_X->value, $xmp);
        $xmp = str_replace('{libraryVersion}', Version::VERSION, $xmp);
        $xmp = str_replace('{facturxVersion}', Version::FACTURX_VERSION, $xmp);
        $xmp = str_replace('{facturxProfile}', $profile->toConformanceLevel(), $xmp);

        return $xmp;
    }

    private static function buildAttachmentsArray(string $xml, AttachmentRelationship $relationship, array $additionalAttachments = []): array
    {
        $attachments = [
            [
                'file' => $xml,
                'filename' => XmlFilename::FACTUR_X->value,
                'relationship' => $relationship->value,
                'description' => 'Factur-X Invoice',
            ],
        ];

        foreach ($additionalAttachments as $additionalAttachment) {
            $attachments[] = [
                'file' => $additionalAttachment['file'],
                'filename' => $additionalAttachment['filename'] ?? basename($additionalAttachment['filename']),
                'relationship' => $additionalAttachment['relationship']->value ?? AttachmentRelationship::UNSPECIFIED->value,
                'description' => $additionalAttachment['description'] ?? '',
            ];
        }

        return $attachments;
    }
}
