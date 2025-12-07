<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\Unit;
use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Line
{
    public ?string $schemeIdentifier = '0160';

    public ?string $priceQuantityUnit = null;

    public string $invoicedQuantityUnit;

    /**
     * @param ?array<Allowance> $allowances
     * @param ?array<Charge> $charges
     */
    public function __construct(
        public string $identifier,
        public string $name,
        public float $netPrice,
        public float $totalNetPrice,
        public float $invoicedQuantity,
        Unit|string $invoicedQuantityUnit,
        public VatCategory $vatCategory,
        public ?float $vatRate = null,
        public ?float $grossPrice = null,
        public ?float $priceQuantity = null,
        Unit|string|null $priceQuantityUnit = null,
        public ?float $priceDiscount = null,
        public ?array $allowances = null,
        public ?array $charges = null,
        public ?string $standardIdentifier = null,
        SchemeIdentifier|string|null $schemeIdentifier = null,
        public ?string $note = null,
        public ?DateTime $startDate = null,
        public ?DateTime $endDate = null,
    ) {
        $this->schemeIdentifier = Utils::stringOrEnumToString($schemeIdentifier);
        $this->priceQuantityUnit = Utils::stringOrEnumToString($priceQuantityUnit);
        $this->invoicedQuantityUnit = Utils::stringOrEnumToString($invoicedQuantityUnit);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            identifier: $data['identifier'],
            name: $data['name'],
            netPrice: $data['netPrice'],
            totalNetPrice: $data['totalNetPrice'],
            invoicedQuantity: $data['invoicedQuantity'],
            invoicedQuantityUnit: $data['invoicedQuantityUnit'],
            vatCategory: $data['vatCategory'],
            vatRate: $data['vatRate'] ?? null,
            grossPrice: $data['grossPrice'] ?? null,
            priceQuantity: $data['priceQuantity'] ?? null,
            priceQuantityUnit: $data['priceQuantityUnit'] ?? null,
            priceDiscount: $data['priceDiscount'] ?? null,
            allowances: isset(($data['allowances'])) ? array_map([Allowance::class, 'createFromArray'], $data['allowances']) : null,
            charges: isset(($data['charges'])) ? array_map([Charge::class, 'createFromArray'], $data['charges']) : null,
            standardIdentifier: $data['standardIdentifier'] ?? null,
            schemeIdentifier: $data['schemeIdentifier'] ?? null,
            note: $data['note'] ?? null,
            startDate: $data['startDate'] ?? null,
            endDate: $data['endDate'] ?? null,
        );
    }
}
