<?php

namespace MahdiAbderraouf\FacturX\Builders;

class TaxCurrencyCode
{
    public static function build(?string $taxCurrencyCode, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$taxCurrencyCode) {
            return '';
        }

        return '<ram:TaxCurrencyCode>' . $taxCurrencyCode . '</ram:TaxCurrencyCode>';
    }
}
