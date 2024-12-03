<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\VatCategory;

class VatBreakdown
{
    public function __construct(
        public float $vatCategoryTaxAmount,
        public float $vatCategoryTaxableAmount,
        public VatCategory $vatCategory,
        public ?float $percentage = null,
        public ?string $vatExemptionReasonCode = null,
        public ?string $exemptionReason = null,
        public ?string $valueAddedTaxPointDateCode = null,
    ) {
    }
}
