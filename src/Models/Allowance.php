<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\VatCategory;

class Allowance
{
    public bool $isAllowance = true;

    public function __construct(
        public float $amount,
        public VatCategory $vatCategory,
        public ?float $vatRate = null,
        public ?float $percentage = null,
        public ?float $baseAmount = null,
        public ?string $reasonCode = null,
        public ?string $reason = null
    ) {
    }
}
