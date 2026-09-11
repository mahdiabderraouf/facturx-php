<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class URIUniversalCommunicationTest extends TestCase
{
    private const URI = '//ram:SellerTradeParty/ram:URIUniversalCommunication/ram:URIID';

    public function test_escapes_the_scheme_identifier(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['electronicAddressSchemeIdentifier'] = 'EM"<&x';

        $xml = self::buildXml($data);

        $this->assertStringContainsString('schemeID="EM&quot;&lt;&amp;x"', $xml);
        $this->assertStringNotContainsString('schemeID="EM"<&x"', $xml);
        $this->assertXPathValue(self::URI . '/@schemeID', 'EM"<&x', $xml);
    }

    public function test_accepts_a_string_scheme_identifier(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['electronicAddressSchemeIdentifier'] = '0225';

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::URI . '/@schemeID', '0225', $xml);
    }

    public function test_omits_the_element_when_the_address_is_empty(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['electronicAddress'] = '';

        $xml = self::buildXml($data);

        $this->assertXPathMissing('//ram:SellerTradeParty/ram:URIUniversalCommunication', $xml);
    }
}
