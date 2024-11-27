<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use MahdiAbderraouf\FacturX\Enums\XmlFilenames;

class Utils
{
    /**
     * Check if the given parameter is a PDF file.
     */
    public static function isPdf(string $pdfPath): bool
    {
        return file_exists($pdfPath) &&
            pathinfo($pdfPath)['extension'] === 'pdf' &&
            mime_content_type($pdfPath) === 'application/pdf';
    }

    public static function isValidXmlFilenames(array $xmlFilenames): bool
    {
        return count(array_diff(XmlFilenames::values(), $xmlFilenames)) === 0;
    }
}
