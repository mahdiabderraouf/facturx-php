<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use Exception;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;

class XmlExtractor
{
    /**
     * @throws UnableToExtractXmlException
     */
    public static function extract(string $pdfPath, array $searchFilenames): string
    {
        try {
            $xmlAttachmentIndex = self::getXmlAttachmentIndex($pdfPath, $searchFilenames);

            $xml = self::getXmlContent(
                $pdfPath,
                $xmlAttachmentIndex,
            );

            if (isset($tempFile)) {
                @unlink($tempFile);
            }

            return $xml;
        } catch (Exception $exception) {
            throw new UnableToExtractXmlException('Error occured while extracting XML : ' . $exception->getMessage());
        }
    }

    /**
     * @throws UnableToExtractXmlException
     */
    private static function getXmlAttachmentIndex(string $pdfPath, array $searchFilenames): int
    {
        $pdfPath = escapeshellarg($pdfPath);

        exec('pdfdetach -list ' . $pdfPath, $output, $resultCode);

        if (0 !== $resultCode) {
            throw new UnableToExtractXmlException('failed to list attachments');
        }

        /*
         * Example output :
         * 1 embedded files
         * 1: factur-x.xml
         */
        foreach ($output as $outputLine) {
            foreach ($searchFilenames as $searchFilename) {
                if (strpos($outputLine, (string) $searchFilename)) {
                    return (int) $outputLine[0];
                }
            }
        }

        throw new UnableToExtractXmlException('No Factur-X XML attachment found');
    }

    /**
     * @throws UnableToExtractXmlException
     */
    private static function getXmlContent(
        string $pdfPath,
        int $attachmentIndex
    ): string {
        $pdfPath = escapeshellarg($pdfPath);
        $attachmentIndex = escapeshellarg($attachmentIndex);
        $xmlOutputPath = tempnam(sys_get_temp_dir(), time());
        $escapedXmlOutputPath = escapeshellarg($xmlOutputPath);

        exec(
            sprintf('pdfdetach -save %s %s -o %s', $attachmentIndex, $pdfPath, $escapedXmlOutputPath),
            $output,
            $resultCode
        );

        if (0 !== $resultCode) {
            throw new UnableToExtractXmlException('failed to extract XML file');
        }

        $xml = @file_get_contents($xmlOutputPath);

        @unlink($xmlOutputPath);

        return $xml;
    }
}
