<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Models\Payee;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class PayeeTest extends TestCase
{
    #[TestWith([SchemeIdentifier::SIREN, '0002'])]
    #[TestWith(['0003', '0003'])]
    #[TestWith([null, ''])]
    public function test_stores_scheme_identifiers_as_strings_with_empty_for_null(
        SchemeIdentifier|string|null $scheme,
        string $expected
    ): void {
        $payee = new Payee(
            'Collector',
            globalIdentifierSchemeIdentifier: $scheme,
            legalRegistrationSchemeIdentifier: $scheme
        );

        $this->assertSame($expected, $payee->globalIdentifierSchemeIdentifier);
        $this->assertSame($expected, $payee->legalRegistrationSchemeIdentifier);
    }

    public function test_create_from_array_defaults_every_identifier_to_an_empty_string(): void
    {
        $payee = Payee::createFromArray(['name' => 'Collector']);

        $this->assertSame('', $payee->identifier);
        $this->assertSame('', $payee->globalIdentifier);
        $this->assertSame('', $payee->globalIdentifierSchemeIdentifier);
        $this->assertSame('', $payee->legalRegistrationIdentifier);
        $this->assertSame('', $payee->legalRegistrationSchemeIdentifier);
    }
}
