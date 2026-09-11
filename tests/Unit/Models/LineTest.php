<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\Unit;
use MahdiAbderraouf\FacturX\Models\Line;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class LineTest extends TestCase
{
    #[TestWith([Unit::ONE, 'C62'])]
    #[TestWith(['XBX', 'XBX'])]
    public function test_stores_the_invoiced_quantity_unit_as_a_string(Unit|string $unit, string $expected): void
    {
        $line = Line::createFromArray([...self::lineData(), 'invoicedQuantityUnit' => $unit]);

        $this->assertSame($expected, $line->invoicedQuantityUnit);
    }

    #[TestWith([Unit::NUMBER_OF_ARTICLES, 'NAR'])]
    #[TestWith(['XBX', 'XBX'])]
    #[TestWith([null, null])]
    public function test_stores_the_price_quantity_unit_as_a_nullable_string(
        Unit|string|null $unit,
        ?string $expected
    ): void {
        $line = Line::createFromArray([...self::lineData(), 'priceQuantityUnit' => $unit]);

        $this->assertSame($expected, $line->priceQuantityUnit);
    }

    public function test_scheme_identifier_defaults_to_gtin(): void
    {
        $line = Line::createFromArray(self::lineData());

        $this->assertSame('0160', $line->schemeIdentifier);
    }

    #[TestWith([SchemeIdentifier::SIREN, '0002'])]
    #[TestWith(['0088', '0088'])]
    public function test_stores_a_given_scheme_identifier_as_a_string(
        SchemeIdentifier|string $scheme,
        string $expected
    ): void {
        $line = Line::createFromArray([...self::lineData(), 'schemeIdentifier' => $scheme]);

        $this->assertSame($expected, $line->schemeIdentifier);
    }

    private static function lineData(): array
    {
        return self::invoiceData('basic')['lines'][0];
    }
}
