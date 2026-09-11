<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class SpecifiedLegalOrganizationTest extends TestCase
{
    private const SELLER_ORG = '//ram:SellerTradeParty/ram:SpecifiedLegalOrganization';
    private const BUYER_ORG = '//ram:BuyerTradeParty/ram:SpecifiedLegalOrganization';

    public function test_includes_the_trading_name_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::SELLER_ORG . '/ram:ID', 'SIRET-SELLER-67890', $xml);
        $this->assertXPathValue(self::SELLER_ORG . '/ram:TradingBusinessName', 'ACME CORPORATION', $xml);
    }

    public function test_omits_the_trading_name_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::SELLER_ORG . '/ram:ID', 'SIRET-SELLER-67890', $xml);
        $this->assertXPathMissing(self::SELLER_ORG . '/ram:TradingBusinessName', $xml);
    }

    public function test_renders_the_trading_name_without_a_registration_identifier(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['seller']['legalRegistrationIdentifier']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::SELLER_ORG . '/ram:ID', $xml);
        $this->assertXPathValue(self::SELLER_ORG . '/ram:TradingBusinessName', 'ACME CORPORATION', $xml);
    }

    public function test_omits_the_element_without_identifier_or_trading_name(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['buyer']['legalRegistrationIdentifier']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::BUYER_ORG, $xml);
    }

    public function test_scheme_defaults_to_0009_and_accepts_an_enum(): void
    {
        $xml = self::buildXml(self::invoiceData('minimum'));

        $this->assertXPathValue(self::SELLER_ORG . '/ram:ID/@schemeID', '0009', $xml);
        $this->assertXPathValue(self::BUYER_ORG . '/ram:ID/@schemeID', '0002', $xml);
    }
}
