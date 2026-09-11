<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class SellerTradePartyTest extends TestCase
{
    private const SELLER = '//ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty';

    public function test_always_includes_name_vat_registration_and_country(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::SELLER . '/ram:Name', 'ACME Corp.', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:SpecifiedTaxRegistration/ram:ID', 'FR2151481365', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:SpecifiedTaxRegistration/ram:ID/@schemeID', 'VA', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:PostalTradeAddress/ram:CountryID', 'FR', $xml);
    }

    public function test_includes_identifiers_global_identifiers_and_email_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::SELLER . '/ram:ID', 'S-2001', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:GlobalID', 'GLOBAL-IDENTIFIER-SELLER', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:GlobalID/@schemeID', '0002', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:URIUniversalCommunication/ram:URIID', 'sales@acmecorp.com', $xml);
        $this->assertXPathValue(self::SELLER . '/ram:URIUniversalCommunication/ram:URIID/@schemeID', 'EM', $xml);
    }

    public function test_omits_identifiers_global_identifiers_and_email_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SELLER . '/ram:ID', $xml);
        $this->assertXPathMissing(self::SELLER . '/ram:GlobalID', $xml);
        $this->assertXPathMissing(self::SELLER . '/ram:URIUniversalCommunication', $xml);
    }

    public function test_omits_vat_registration_when_the_identifier_is_empty(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['vatIdentifier'] = '';

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SELLER . '/ram:SpecifiedTaxRegistration', $xml);
    }
}
