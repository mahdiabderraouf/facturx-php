<?php

namespace MahdiAbderraouf\FacturX;

use DOMDocument;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Validator
{
    /**
     * Validate Factur-X PDF or XML against XSD schema
     *
     * @param  string $source Path for PDF or XML file or XML string
     *
     * @throws InvalidArgumentException
     * @throws InvalidXmlException
     */
    public static function validate(string $source, ?Profile $profile = null): string
    {
        $xml = self::resolveXmlFromSource($source);

        $domDocument = new DOMDocument();
        $domDocument->loadXML($xml);

        $profile ??= Parser::getProfile($xml);

        libxml_use_internal_errors(true);
        if (!$domDocument->schemaValidate(self::getXsdFilePathByProfile($profile))) {
            $xmlErrors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors(false);

            throw new InvalidXmlException('Invalid Factur-X XML', errors: $xmlErrors);
        }

        libxml_use_internal_errors(false);

        return true;
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function resolveXmlFromSource(string $source): string
    {
        if (!@is_file($source)) {
            return $source;
        }

        try {
            return Utils::isXmlFile($source) ?
                file_get_contents($source) :
                Parser::getXml($source);
        } catch (UnableToExtractXmlException) {
            throw new InvalidArgumentException('Invalid argument $source : not a Factur-X file');
        }
    }

    private static function getXsdFilePathByProfile(Profile $profile): string
    {
        $xsdFilePath = match ($profile) {
            Profile::MINIMUM => 'minimum/Factur-X_1.0.07_MINIMUM.xsd',
            Profile::BASIC_WL => 'basic-wl/Factur-X_1.0.07_BASICWL.xsd',
            Profile::BASIC => 'basic/Factur-X_1.0.07_BASIC.xsd',
            Profile::EN16931 => 'en16931/Factur-X_1.0.07_EN16931.xsd',
            Profile::EXTENDED => 'extended/Factur-X_1.0.07_EXTENDED.xsd',
        };

        return __DIR__ . '/../resources/xsd/' . $xsdFilePath;
    }
}