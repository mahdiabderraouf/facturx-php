<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Models\Allowance;
use MahdiAbderraouf\FacturX\Models\Charge;
use MahdiAbderraouf\FacturX\Models\Delivery;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Line;
use MahdiAbderraouf\FacturX\Models\Note;
use MahdiAbderraouf\FacturX\Models\Payee;
use MahdiAbderraouf\FacturX\Models\Payment;
use MahdiAbderraouf\FacturX\Models\Payterm;
use MahdiAbderraouf\FacturX\Models\VatBreakdown;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class InvoiceTest extends TestCase
{
    #[TestWith(['currencyCode'])]
    #[TestWith(['vatCurrency'])]
    #[TestWith(['vatAccountingCurrencyCode'])]
    public function test_rejects_a_currency_code_that_is_not_three_characters(string $field): void
    {
        $this->expectException(InvalidArgumentException::class);

        Invoice::createFromArray([...self::invoiceData('minimum'), $field => 'EU']);
    }

    #[TestWith(['currencyCode'])]
    #[TestWith(['vatCurrency'])]
    #[TestWith(['vatAccountingCurrencyCode'])]
    public function test_uppercases_currency_codes(string $field): void
    {
        $invoice = Invoice::createFromArray([...self::invoiceData('minimum'), $field => 'usd']);

        $this->assertSame('USD', $invoice->{$field});
    }

    public function test_accepts_an_absent_vat_accounting_currency(): void
    {
        $invoice = Invoice::createFromArray(self::invoiceData('minimum'));

        $this->assertSame('', $invoice->vatAccountingCurrencyCode);
    }

    #[TestWith([InvoiceTypeCode::COMMERCIAL_INVOICE])]
    #[TestWith(['380'])]
    public function test_stores_the_type_code_as_its_string_value(InvoiceTypeCode|string $typeCode): void
    {
        $invoice = Invoice::createFromArray([...self::invoiceData('minimum'), 'typeCode' => $typeCode]);

        $this->assertSame('380', $invoice->typeCode);
    }

    public function test_create_from_array_maps_nested_arrays_to_models(): void
    {
        $invoice = Invoice::createFromArray(self::invoiceData('basic'));

        $this->assertCount(3, $invoice->lines);
        $this->assertContainsOnlyInstancesOf(Line::class, $invoice->lines);
        $this->assertContainsOnlyInstancesOf(Note::class, $invoice->notes);
        $this->assertContainsOnlyInstancesOf(VatBreakdown::class, $invoice->vatBreakdowns);
        $this->assertContainsOnlyInstancesOf(Allowance::class, $invoice->allowances);
        $this->assertContainsOnlyInstancesOf(Charge::class, $invoice->charges);
        $this->assertInstanceOf(Delivery::class, $invoice->delivery);
        $this->assertInstanceOf(Payee::class, $invoice->payee);
        $this->assertInstanceOf(Payment::class, $invoice->payment);
        $this->assertInstanceOf(Payterm::class, $invoice->payterm);
        $this->assertSame('F-2024-11-15-0001', $invoice->precedingInvoices[0]['reference']);
    }

    public function test_create_from_array_leaves_absent_optional_sections_null(): void
    {
        $invoice = Invoice::createFromArray(self::invoiceData('minimum'));

        $this->assertNull($invoice->lines);
        $this->assertNull($invoice->notes);
        $this->assertNull($invoice->vatBreakdowns);
        $this->assertNull($invoice->allowances);
        $this->assertNull($invoice->charges);
        $this->assertNull($invoice->delivery);
        $this->assertNull($invoice->payee);
        $this->assertNull($invoice->payment);
        $this->assertNull($invoice->payterm);
        $this->assertNull($invoice->precedingInvoices);
        $this->assertNull($invoice->paidAmount);
    }

    public function test_create_from_array_defaults_reference_strings_to_empty_not_null(): void
    {
        $invoice = Invoice::createFromArray(self::invoiceData('minimum'));

        $this->assertSame('', $invoice->bankAssignedCreditorIdentifier);
        $this->assertSame('', $invoice->remittanceInformation);
        $this->assertSame('', $invoice->vatAccountingCurrencyCode);
        $this->assertSame('A2', $invoice->businessProcessType);
    }
}
