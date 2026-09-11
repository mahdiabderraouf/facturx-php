<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Models\Address;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AddressTest extends TestCase
{
    #[TestWith(['FRA'])]
    #[TestWith(['F'])]
    #[TestWith([''])]
    public function test_rejects_a_country_code_that_is_not_two_characters(string $countryCode): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Address($countryCode);
    }

    public function test_uppercases_the_country_code(): void
    {
        $address = Address::createFromArray(['countryCode' => 'fr']);

        $this->assertSame('FR', $address->countryCode);
    }

    public function test_create_from_array_defaults_optional_fields_to_empty_strings(): void
    {
        $address = Address::createFromArray(['countryCode' => 'FR']);

        $this->assertSame('', $address->postCode);
        $this->assertSame('', $address->address1);
        $this->assertSame('', $address->city);
        $this->assertSame('', $address->province);
    }
}
