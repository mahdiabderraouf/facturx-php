<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\VatCategory;

class Allowance
{
    public bool $isAllowance = true;

    public function __construct(
        public float $amount,
        public ?VatCategory $vatCategory = null,
        public ?float $vatRate = null,
        public ?float $percentage = null,
        public ?float $baseAmount = null,
        public ?string $reasonCode = null,
        public ?string $reason = null
    ) {}

    public static function createFromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            vatCategory: $data['vatCategory'],
            vatRate: $data['vatRate'] ?? null,
            percentage: $data['percentage'] ?? null,
            baseAmount: $data['baseAmount'] ?? null,
            reasonCode: $data['reasonCode'] ?? null,
            reason: $data['reason'] ?? null
        );
    }
}
