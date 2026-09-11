<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Models;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Seller;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ElectronicAddressTest extends TestCase
{
    #[TestWith([Seller::class])]
    #[TestWith([Buyer::class])]
    public function test_electronic_address_keys_are_stored_and_mirrored_to_the_deprecated_email_properties(
        string $model
    ): void {
        $party = $model::createFromArray([
            ...self::partyData($model),
            'electronicAddress' => '123456789',
            'electronicAddressSchemeIdentifier' => SchemeIdentifier::FRCTC_ELECTRONIC_ADDRESS,
        ]);

        $this->assertSame('123456789', $party->electronicAddress);
        $this->assertSame('0225', $party->electronicAddressSchemeIdentifier);
        $this->assertSame('123456789', $party->email);
        $this->assertSame('0225', $party->emailSchemeIdentifier);
    }

    #[TestWith([Seller::class])]
    #[TestWith([Buyer::class])]
    public function test_deprecated_email_keys_still_populate_the_electronic_address(string $model): void
    {
        $party = $model::createFromArray([
            ...self::partyData($model),
            'email' => 'legacy@example.com',
            'emailSchemeIdentifier' => '0225',
        ]);

        $this->assertSame('legacy@example.com', $party->electronicAddress);
        $this->assertSame('0225', $party->electronicAddressSchemeIdentifier);
    }

    #[TestWith([Seller::class])]
    #[TestWith([Buyer::class])]
    public function test_electronic_address_defaults_to_empty_with_scheme_em(string $model): void
    {
        $party = $model::createFromArray(self::partyData($model));

        $this->assertSame('', $party->electronicAddress);
        $this->assertSame('EM', $party->electronicAddressSchemeIdentifier);
    }

    private static function partyData(string $model): array
    {
        return self::invoiceData('minimum')[$model === Seller::class ? 'seller' : 'buyer'];
    }
}
