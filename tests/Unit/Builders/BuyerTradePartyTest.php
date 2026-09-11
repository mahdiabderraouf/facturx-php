<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class BuyerTradePartyTest extends TestCase
{
    private const BUYER = '//ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty';

    public function test_includes_identifiers_address_email_and_vat_registration_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::BUYER . '/ram:ID', 'B-1001', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:GlobalID', 'GLOBAL-IDENTIFIER-BUYER', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:GlobalID/@schemeID', '0002', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:PostalTradeAddress/ram:CountryID', 'FR', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:URIUniversalCommunication/ram:URIID', 'johndoe@email.com', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:SpecifiedTaxRegistration/ram:ID', 'FR21514164516451', $xml);
    }

    public function test_keeps_only_name_and_legal_organization_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::BUYER . '/*', 2, $xml);
        $this->assertXPathValue(self::BUYER . '/ram:Name', 'John Doe Inc.', $xml);
        $this->assertXPathValue(self::BUYER . '/ram:SpecifiedLegalOrganization/ram:ID', 'SIRET-BUYER-12345', $xml);
    }

    public function test_omits_name_when_empty(): void
    {
        $data = self::invoiceData('basicwl');
        $data['buyer']['name'] = '';

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::BUYER . '/ram:Name', $xml);
    }
}
