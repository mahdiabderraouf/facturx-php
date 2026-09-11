<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class ApplicableHeaderTradeDeliveryTest extends TestCase
{
    private const DELIVERY = '//ram:ApplicableHeaderTradeDelivery';
    private const SHIP_TO = self::DELIVERY . '/ram:ShipToTradeParty';
    private const DATE = self::DELIVERY
        . '/ram:ActualDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString';

    public function test_is_an_empty_element_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::DELIVERY, 1, $xml);
        $this->assertXPathCount(self::DELIVERY . '/*', 0, $xml);
    }

    public function test_is_an_empty_element_without_delivery_data(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['delivery']);

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::DELIVERY, 1, $xml);
        $this->assertXPathCount(self::DELIVERY . '/*', 0, $xml);
    }

    public function test_includes_ship_to_party_delivery_date_and_despatch_advice_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::SHIP_TO . '/ram:ID', 'LOC-1234', $xml);
        $this->assertXPathValue(self::SHIP_TO . '/ram:GlobalID', 'GLOBAL-LOC-5678', $xml);
        $this->assertXPathValue(self::SHIP_TO . '/ram:GlobalID/@schemeID', '0088', $xml);
        $this->assertXPathValue(self::SHIP_TO . '/ram:Name', 'John Doe Inc.', $xml);
        $this->assertXPathValue(self::SHIP_TO . '/ram:PostalTradeAddress/ram:CityName', 'Paris', $xml);
        $this->assertXPathValue(self::DATE, '20241210', $xml);
        $this->assertXPathValue(self::DATE . '/@format', '102', $xml);
        $this->assertXPathValue(
            self::DELIVERY . '/ram:DespatchAdviceReferencedDocument/ram:IssuerAssignedID',
            'ISSUER-12345',
            $xml
        );
    }

    public function test_omits_every_optional_delivery_part_when_unset(): void
    {
        $data = self::invoiceData('basicwl');
        $data['delivery'] = ['partyName' => 'Warehouse'];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::SHIP_TO . '/*', 1, $xml);
        $this->assertXPathMissing(self::SHIP_TO . '/ram:ID', $xml);
        $this->assertXPathMissing(self::SHIP_TO . '/ram:GlobalID', $xml);
        $this->assertXPathMissing(self::SHIP_TO . '/ram:PostalTradeAddress', $xml);
        $this->assertXPathMissing(self::DELIVERY . '/ram:ActualDeliverySupplyChainEvent', $xml);
        $this->assertXPathMissing(self::DELIVERY . '/ram:DespatchAdviceReferencedDocument', $xml);
    }
}
