<?php

namespace MahdiAbderraouf\FacturX;

use DOMXPath;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Helpers\XmlExtractor;

class Parser
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
        $domXPath = Utils::getDomXPath($xml);

        return [
            'issueDate' => DateFormat102::fromFormat102(
                $domXPath->query('//rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString')->item(0)->nodeValue
            ),
            'supplier' => $domXPath->query('//ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty/ram:Name')->item(0)->nodeValue,
            'documentNumber' => $domXPath->query('//rsm:ExchangedDocument/ram:ID')->item(0)->nodeValue,
        ];
    }

    /**
     * @throws InvalidXmlException
     */
    public static function getProfile(string $xml): Profile
    {
        $domXPath = Utils::getDomXPath($xml);

        $profileNode = $domXPath->query(
            '//rsm:ExchangedDocumentContext/ram:GuidelineSpecifiedDocumentContextParameter/ram:ID'
        );

        if (!$profileNode || $profileNode->length !== 1) {
            throw new InvalidXmlException('Invalid Factur-X XML : invalid or missing profile tag');
        }

        $profile = Profile::tryFrom($profileNode->item(0)->nodeValue);

        if (!$profile) {
            throw new InvalidXmlException('Invalid Factur-X XML : invalid profile found');
        }

        return $profile;
    }
}
