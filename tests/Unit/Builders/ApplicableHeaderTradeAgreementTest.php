<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class ApplicableHeaderTradeAgreementTest extends TestCase
{
    private const AGREEMENT = '//ram:ApplicableHeaderTradeAgreement';
    private const TAX_REP = self::AGREEMENT . '/ram:SellerTaxRepresentativeTradeParty';

    public function test_includes_buyer_and_order_references_at_minimum(): void
    {
        $xml = self::buildXml(self::invoiceData('minimum'));

        $this->assertXPathValue(self::AGREEMENT . '/ram:BuyerReference', 'BUYER-0001', $xml);
        $this->assertXPathValue(
            self::AGREEMENT . '/ram:BuyerOrderReferencedDocument/ram:IssuerAssignedID',
            'PO-202400005',
            $xml
        );
    }

    public function test_omits_buyer_and_order_references_when_unset(): void
    {
        $data = self::invoiceData('minimum');
        unset($data['buyer']['buyerReference'], $data['purchaseOrderReference']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::AGREEMENT . '/ram:BuyerReference', $xml);
        $this->assertXPathMissing(self::AGREEMENT . '/ram:BuyerOrderReferencedDocument', $xml);
    }

    public function test_includes_tax_representative_and_contract_reference_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::TAX_REP . '/ram:Name', 'Tax Representative', $xml);
        $this->assertXPathValue(self::TAX_REP . '/ram:PostalTradeAddress/ram:CityName', 'Paris', $xml);
        $this->assertXPathValue(self::TAX_REP . '/ram:SpecifiedTaxRegistration/ram:ID', 'FR232554', $xml);
        $this->assertXPathValue(
            self::AGREEMENT . '/ram:ContractReferencedDocument/ram:IssuerAssignedID',
            'Contract-001',
            $xml
        );
    }

    public function test_omits_tax_representative_and_contract_reference_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::TAX_REP, $xml);
        $this->assertXPathMissing(self::AGREEMENT . '/ram:ContractReferencedDocument', $xml);
    }

    public function test_omits_tax_representative_when_unset(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['seller']['taxRespresentative']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::TAX_REP, $xml);
    }
}
