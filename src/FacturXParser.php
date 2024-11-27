<?php

namespace MahdiAbderraouf\FacturX;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\XmlFilenames;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Helpers\XmlExtractor;

class FacturXParser
{
    /**
     * Get Factur-X XML from a PDF file
     *
     * @throws InvalidArgumentException
     * @throws NotPdfFileException
     * @throws UnableToExtractXmlException
     */
    public static function getXML(string $pdfPath, string|array|null $xmlFilename = null): string
    {
        if (!Utils::isPdf($pdfPath)) {
            throw new NotPdfFileException('The file ' . $pdfPath . ' is not a PDF file');
        }

        $xmlFilename ??= XmlFilenames::values();
        $xmlFilename = is_string($xmlFilename) ? [$xmlFilename] : $xmlFilename;

        if (!Utils::isValidXmlFilenames($xmlFilename)) {
            throw new InvalidArgumentException('Invalid argument $xmlFilename');
        }

        return XmlExtractor::extract($pdfPath, $xmlFilename);
    }
}
