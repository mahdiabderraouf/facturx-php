<?php

namespace MahdiAbderraouf\FacturX;

use DOMDocument;
use DOMXPath;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
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
    public static function getXml(string $pdfPath, string|array|null $xmlFilename = null): string
    {
        if (!Utils::isPdfFile($pdfPath)) {
            throw new NotPdfFileException('The file ' . $pdfPath . ' is not a PDF file');
        }

        $xmlFilename ??= XmlFilename::values();
        $xmlFilename = is_string($xmlFilename) ? [$xmlFilename] : $xmlFilename;

        if (!Utils::isValidXmlFilenames($xmlFilename)) {
            throw new InvalidArgumentException('Invalid argument $xmlFilename');
        }

        return XmlExtractor::extract($pdfPath, $xmlFilename);
    }

    public static function extractBaseData(string $xml): array
    {
        $domDocument = new DOMDocument();
        $domDocument->loadXML(is_file($xml) ? file_get_contents($xml) : $xml);
        $domXPath = new DOMXPath($domDocument);

        return [
            'issueDate' => DateFormat102::fromFormat102(
                $domXPath->query('//rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString')->item(0)->nodeValue
            ),
            'supplier' => $domXPath->query('//ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name')->item(0)->nodeValue,
            'documentNumber' => $domXPath->query('//rsm:ExchangedDocument/ram:ID')->item(0)->nodeValue,
        ];
    }
}
