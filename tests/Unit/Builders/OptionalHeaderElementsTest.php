<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class OptionalHeaderElementsTest extends TestCase
{
    public static function optionalElements(): array
    {
        $noCreditor = self::invoiceData('basicwl');
        $noCreditor['bankAssignedCreditorIdentifier'] = null;

        $noRemittance = self::invoiceData('basicwl');
        $noRemittance['remittanceInformation'] = null;

        $noAccounting = self::invoiceData('basicwl');
        unset($noAccounting['buyer']['accountingReference']);

        $noDespatch = self::invoiceData('basicwl');
        unset($noDespatch['delivery']['issuerAssignedID']);

        $noContract = self::invoiceData('basicwl');
        unset($noContract['contractReference']);

        return [
            'creditor reference' => [$noCreditor, '//ram:CreditorReferenceID'],
            'payment reference' => [$noRemittance, '//ram:PaymentReference'],
            'accounting account' => [$noAccounting, '//ram:ReceivableSpecifiedTradeAccountingAccount'],
            'despatch advice' => [$noDespatch, '//ram:DespatchAdviceReferencedDocument'],
            'contract reference' => [$noContract, '//ram:ContractReferencedDocument'],
        ];
    }

    #[DataProvider('optionalElements')]
    public function test_omits_the_element_when_its_value_is_empty(array $invoiceData, string $xpath): void
    {
        $xml = self::buildXml($invoiceData);

        $this->assertXPathMissing($xpath, $xml);
    }
}
