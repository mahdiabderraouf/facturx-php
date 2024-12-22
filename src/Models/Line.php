<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\Unit;
use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Line
{
    public string $schemeIdentifier = '0009';
    public ?string $priceQuantityUnit = null;
    public ?string $invoicedQuantityUnit = null;

    /**
     * @param ?array<Allowance> $allowances
     * @param ?array<Charge> $charges
     */
    public function __construct(
        public string $identifier,
        public string $name,
        public float $netPrice,
        public float $totalNetPrice,
        public VatCategory $vatCategory,
        public ?float $vatRate = null,
        public ?float $grossPrice = null,
        public ?float $priceQuantity = null,
        Unit|string|null $priceQuantityUnit = null,
        public ?float $invoicedQuantity = null,
        Unit|string|null $invoicedQuantityUnit = null,
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
        $this->priceQuantityUnit = Utils::stringOrEnumToString($schemeIdentifier);
        $this->invoicedQuantity = Utils::stringOrEnumToString($schemeIdentifier);
    }
}
