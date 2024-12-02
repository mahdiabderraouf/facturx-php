<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\VatCategory;

class Allowance
{
    public bool $isAllowance = true;

    public function __construct(
        public VatCategory $vatCategory,
        public VatCategory $vatRate,
        public ?float $percentage = null,
        public ?float $amount = null,
        public ?float $baseAmount = null,
        public ?string $reasonCode = null,
        public ?string $reason = null
    ) {}
}
