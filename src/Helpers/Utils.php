<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use MahdiAbderraouf\FacturX\Enums\FacturXProfile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;

class Utils
{
    /**
     * Check if the given $pdfPath is a PDF file.
     */
    public static function isPdfFile(string $pdfPath): bool
    {
        return file_exists($pdfPath) &&
            pathinfo($pdfPath)['extension'] === 'pdf' &&
            mime_content_type($pdfPath) === 'application/pdf';
    }

    /**
     * Check if the given $xmlPath is an XML file.
     */
    public static function isXmlFile(string $xmlPath): bool
    {
        return file_exists($xmlPath) &&
            pathinfo($xmlPath)['extension'] === 'xml' &&
            in_array(mime_content_type($xmlPath), ['application/xml', 'text/xml']);
    }

    /**
     * Check if given $XmlFilename are valid Factur-x XML filenames
     */
    public static function isValidXmlFilenames(array $XmlFilename): bool
    {
        return count(array_diff(XmlFilename::values(), $XmlFilename)) === 0;
    }

    /**
     * Check if given $profile is a valid Factur-X profile
     */
    public static function isValidProfile(string $profile): bool
    {
        return in_array($profile, FacturXProfile::values());
    }
}
