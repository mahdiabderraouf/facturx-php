<?php

namespace MahdiAbderraouf\FacturX\Builders;

class TaxCurrencyCode
{
    public static function build(?string $taxCurrencyCode): string
    {
        if (!$taxCurrencyCode) {
            return '';
        }

        return '<ram:TaxCurrencyCode>' . $taxCurrencyCode . '</ram:TaxCurrencyCode>';
    }
}
