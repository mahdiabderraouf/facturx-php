<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ApplicableTradeTax
{
    public static function build(?array $vatBreakdowns, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$vatBreakdowns) {
            return '';
        }

        $xml = '';
        foreach ($vatBreakdowns as $vatBreakdown) {
            $xml .= '<ram:ApplicableTradeTax>';
            $xml .= '<ram:CalculatedAmount>' . $vatBreakdown->vatCategoryTaxAmount . '</ram:CalculatedAmount>';
            $xml .= '<ram:TypeCode>VAT</ram:TypeCode>';

            if ($vatBreakdown->exemptionReason) {
                $xml .= '<ram:ExemptionReason>' . $vatBreakdown->exemptionReason . '</ram:ExemptionReason>';
            }

            $xml .= '<ram:BasisAmount>' . $vatBreakdown->vatCategoryTaxableAmount . '</ram:BasisAmount>';
            $xml .= '<ram:CategoryCode>' . $vatBreakdown->vatCategory->value . '</ram:CategoryCode>';

            if ($vatBreakdown->vatExemptionReasonCode) {
                $xml .= '<ram:ExemptionReasonCode>' . $vatBreakdown->vatExemptionReasonCode . '</ram:ExemptionReasonCode>';
            }

            if ($vatBreakdown->valueAddedTaxPointDateCode) {
                $xml .= '<ram:DueDateTypeCode>' . $vatBreakdown->valueAddedTaxPointDateCode . '</ram:DueDateTypeCode>';
            }

            if ($vatBreakdown->percentage) {
                $xml .= '<ram:RateApplicablePercent>' . $vatBreakdown->percentage . '</ram:RateApplicablePercent>';
            }

            $xml .= '</ram:ApplicableTradeTax>';
        }

        return $xml;
    }
}
