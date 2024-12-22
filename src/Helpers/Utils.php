<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use BackedEnum;
use DOMDocument;
use DOMXPath;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;

class Utils
{
    /**
     * Check if the given $pdfPath is a PDF file.
     */
    public static function isPdfFile(string $pdfPath): bool
    {
        return file_exists($pdfPath) &&
            mime_content_type($pdfPath) === 'application/pdf';
    }

    /**
     * Check if the given $xmlPath is an XML file.
     */
    public static function isXmlFile(string $xmlPath): bool
    {
        return file_exists($xmlPath) &&
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
        return in_array($profile, Profile::values());
    }

    public static function getDomXPath(string $xml): DOMXPath
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML(is_file($xml) ? file_get_contents($xml) : $xml);

        return new DOMXPath($domDocument);
    }

    public static function stringOrEnumToString(string|BackedEnum|null $data): ?string
    {
        return $data instanceof BackedEnum ? $data->value : $data;
    }
}
