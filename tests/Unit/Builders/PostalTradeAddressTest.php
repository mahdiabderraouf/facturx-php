<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class PostalTradeAddressTest extends TestCase
{
    private const ADDRESS = '//ram:SellerTradeParty/ram:PostalTradeAddress';

    public function test_includes_every_address_part_at_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['address']['address2'] = 'Bâtiment B';
        $data['seller']['address']['address3'] = 'Étage 3';

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::ADDRESS . '/ram:PostcodeCode', '69001', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:LineOne', '5 Rue des Alpes', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:LineTwo', 'Bâtiment B', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:LineThree', 'Étage 3', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:CityName', 'Lyon', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:CountryID', 'FR', $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:CountrySubDivisionName', 'Auvergne-Rhône-Alpes', $xml);
    }

    public function test_keeps_only_the_country_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::ADDRESS . '/*', 1, $xml);
        $this->assertXPathValue(self::ADDRESS . '/ram:CountryID', 'FR', $xml);
    }

    public function test_omits_empty_optional_parts_at_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['seller']['address'] = ['countryCode' => 'FR', 'city' => 'Lyon'];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::ADDRESS . '/*', 2, $xml);
        $this->assertXPathMissing(self::ADDRESS . '/ram:PostcodeCode', $xml);
        $this->assertXPathMissing(self::ADDRESS . '/ram:LineOne', $xml);
        $this->assertXPathMissing(self::ADDRESS . '/ram:CountrySubDivisionName', $xml);
    }
}
