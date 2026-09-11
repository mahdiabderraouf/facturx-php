<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use BackedEnum;
use DOMDocument;
use DOMXPath;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;

class Utils
{
    private const XML_NAMESPACES = [
        'rsm' => 'urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100',
        'ram' => 'urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100',
        'udt' => 'urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100',
        'qdt' => 'urn:un:unece:uncefact:data:standard:QualifiedDataType:100',
    ];

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
        if (!file_exists($xmlPath)) {
            return false;
        }

        if (!in_array(mime_content_type($xmlPath), ['application/xml', 'text/xml', 'text/plain'])) {
            return false;
        }

        return str_starts_with(ltrim((string) file_get_contents($xmlPath, length: 512)), '<');
    }

    /**
     * Check if given $XmlFilename are valid Factur-x XML filenames
     */
    public static function isValidXmlFilenames(array $XmlFilename): bool
    {
        return array_diff(XmlFilename::values(), $XmlFilename) === [];
    }

    /**
     * Check if given $profile is a valid Factur-X profile
     */
    public static function isValidProfile(string $profile): bool
    {
        return in_array($profile, Profile::values());
    }

    /**
     * @param  string $xml XML file path or XML string
     *
     * @throws InvalidXmlException
     */
    public static function loadXml(string $xml): DOMDocument
    {
        $xml = is_file($xml) ? (string) file_get_contents($xml) : $xml;

        $domDocument = new DOMDocument();

        if (trim($xml) === '' || !@$domDocument->loadXML($xml)) {
            throw new InvalidXmlException('Invalid Factur-X XML');
        }

        return $domDocument;
    }

    /**
     * @throws InvalidXmlException
     */
    public static function getDomXPath(string $xml): DOMXPath
    {
        $domXPath = new DOMXPath(self::loadXml($xml));

        foreach (self::XML_NAMESPACES as $prefix => $uri) {
            $domXPath->registerNamespace($prefix, $uri);
        }

        return $domXPath;
    }

    public static function stringOrEnumToString(string|BackedEnum|null $data): ?string
    {
        return $data instanceof BackedEnum ? $data->value : $data;
    }
}
