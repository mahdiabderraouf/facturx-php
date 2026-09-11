<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class PayeeTradePartyTest extends TestCase
{
    private const PAYEE = '//ram:ApplicableHeaderTradeSettlement/ram:PayeeTradeParty';

    public function test_renders_identifiers_name_and_legal_organization(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::PAYEE . '/ram:ID', 'PAYEE-1001', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:GlobalID', 'GLOBAL-PAYEE-ID', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:GlobalID/@schemeID', '0003', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:Name', 'ACME Corp. Payments', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:SpecifiedLegalOrganization/ram:ID', 'REG-PAYEE-1001', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:SpecifiedLegalOrganization/ram:ID/@schemeID', '0004', $xml);
    }

    public function test_renders_only_the_name_when_identifiers_are_unset(): void
    {
        $data = self::invoiceData('basicwl');
        $data['payee'] = ['name' => 'Collector'];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::PAYEE . '/*', 1, $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:Name', 'Collector', $xml);
    }

    public function test_omits_the_element_without_payee(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['payee']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::PAYEE, $xml);
    }
}
