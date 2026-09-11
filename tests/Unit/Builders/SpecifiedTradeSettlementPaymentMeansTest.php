<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class SpecifiedTradeSettlementPaymentMeansTest extends TestCase
{
    private const MEANS = '//ram:SpecifiedTradeSettlementPaymentMeans';
    private const PAYER = self::MEANS . '/ram:PayerPartyDebtorFinancialAccount';
    private const PAYEE = self::MEANS . '/ram:PayeePartyCreditorFinancialAccount';

    public function test_renders_type_code_and_both_accounts(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::MEANS . '/ram:TypeCode', '20', $xml);
        $this->assertXPathValue(self::PAYER . '/ram:IBANID', 'account-12345', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:IBANID', 'payment-67890', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:ProprietaryID', '9876543210', $xml);
    }

    public function test_omits_the_element_without_payment(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['payment']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::MEANS, $xml);
    }

    public function test_omits_both_accounts_when_only_the_type_code_is_given(): void
    {
        $data = self::invoiceData('basicwl');
        $data['payment'] = ['paymentMeansTypeCode' => '30'];

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::MEANS . '/ram:TypeCode', '30', $xml);
        $this->assertXPathMissing(self::PAYER, $xml);
        $this->assertXPathMissing(self::PAYEE, $xml);
    }

    public function test_renders_only_the_given_payee_account_identifier(): void
    {
        $data = self::invoiceData('basicwl');
        $data['payment'] = ['paymentMeansTypeCode' => '30', 'nationalAccountNumber' => '123'];

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::PAYEE . '/ram:IBANID', $xml);
        $this->assertXPathValue(self::PAYEE . '/ram:ProprietaryID', '123', $xml);
    }
}
