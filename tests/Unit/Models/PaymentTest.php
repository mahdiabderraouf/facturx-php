<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use MahdiAbderraouf\FacturX\Enums\PaymentMeans;
use MahdiAbderraouf\FacturX\Models\Payment;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class PaymentTest extends TestCase
{
    #[TestWith([PaymentMeans::CHECK, '20'])]
    #[TestWith(['30', '30'])]
    public function test_stores_the_payment_means_type_code_as_a_string(
        PaymentMeans|string $code,
        string $expected
    ): void {
        $payment = Payment::createFromArray(['paymentMeansTypeCode' => $code]);

        $this->assertSame($expected, $payment->paymentMeansTypeCode);
    }

    public function test_account_identifiers_default_to_null(): void
    {
        $payment = Payment::createFromArray(['paymentMeansTypeCode' => '30']);

        $this->assertNull($payment->debitedAccountIdentifier);
        $this->assertNull($payment->paymentAccountIdentifier);
        $this->assertNull($payment->nationalAccountNumber);
    }
}
