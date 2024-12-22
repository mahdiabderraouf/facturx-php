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

    public static function createFromArray(array $data): self
    {
        return new self(
            vatCategoryTaxAmount: $data['vatCategoryTaxAmount'],
            vatCategoryTaxableAmount: $data['vatCategoryTaxableAmount'],
            vatCategory: $data['vatCategory'],
            percentage: $data['percentage'] ?? null,
            vatExemptionReasonCode: $data['vatExemptionReasonCode'] ?? null,
            exemptionReason: $data['exemptionReason'] ?? null,
            valueAddedTaxPointDateCode: $data['valueAddedTaxPointDateCode'] ?? null
        );
    }
}
