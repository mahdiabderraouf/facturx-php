<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Tests\TestCase;

class SpecifiedTradePaymentTermsTest extends TestCase
{
    private const TERMS = '//ram:SpecifiedTradePaymentTerms';

    public function test_renders_description_and_due_date_in_format_102(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::TERMS . '/ram:Description', 'Net 30', $xml);
        $this->assertXPathValue(self::TERMS . '/ram:DueDateDateTime/udt:DateTimeString', '20250114', $xml);
        $this->assertXPathValue(self::TERMS . '/ram:DueDateDateTime/udt:DateTimeString/@format', '102', $xml);
        $this->assertXPathMissing(self::TERMS . '/ram:DirectDebitMandateID', $xml);
    }

    public function test_renders_the_direct_debit_mandate_when_set(): void
    {
        $data = self::invoiceData('basicwl');
        $data['payterm']['mandateReferenceIdentifier'] = 'MANDATE-42';

        $xml = self::buildXml($data);

        $this->assertXPathValue(self::TERMS . '/ram:DirectDebitMandateID', 'MANDATE-42', $xml);
    }

    public function test_omits_due_date_and_description_when_unset(): void
    {
        $data = self::invoiceData('basicwl');
        $data['payterm'] = ['mandateReferenceIdentifier' => 'MANDATE-42'];

        $xml = self::buildXml($data);

        $this->assertXPathCount(self::TERMS . '/*', 1, $xml);
        $this->assertXPathMissing(self::TERMS . '/ram:Description', $xml);
        $this->assertXPathMissing(self::TERMS . '/ram:DueDateDateTime', $xml);
    }

    public function test_omits_the_element_without_payment_terms(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['payterm']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::TERMS, $xml);
    }
}
