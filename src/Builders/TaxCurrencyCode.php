<?php

namespace MahdiAbderraouf\FacturX\Builders;

class TaxCurrencyCode
{
    public static function build(?string $vatAccountingCurrencyCode, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$vatAccountingCurrencyCode) {
            return '';
        }

        return '<ram:TaxCurrencyCode>' . $vatAccountingCurrencyCode . '</ram:TaxCurrencyCode>';
    }
}
