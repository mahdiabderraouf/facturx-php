<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Allowance;

class SpecifiedTradeAllowanceCharge
{
    /**
     * @param array<Allowance> $allowances
     */
    public static function build(?array $allowances): string
    {
        if (!$allowances) {
            return '';
        }

        $xml = '<ram:SpecifiedTradeAllowanceCharge>';

        foreach ($allowances as $allowance) {
            $xml .= '<ram:ChargeIndicator>' .
                '<udt:Indicator>' . (!$allowance->isAllowance ? 'true' : 'false') . '</udt:Indicator>' .
            '</ram:ChargeIndicator>';

            if ($allowance->percentage) {
                $xml .= '<ram:CalculationPercent>' . $allowance->percentage . '</ram:CalculationPercent>';
            }

            if ($allowance->baseAmount) {
                $xml .= '<ram:BasisAmount>' . $allowance->baseAmount . '</ram:BasisAmount>';
            }

            $xml .= '<ram:ActualAmount>' . $allowance->amount . '</ram:ActualAmount>';

            if ($allowance->reasonCode) {
                $xml .= '<ram:ReasonCode>' . $allowance->reasonCode . '</ram:ReasonCode>';
            }

            if ($allowance->reason) {
                $xml .= '<ram:Reason>' . $allowance->reason . '</ram:Reason>';
            }

            if ($allowance->vatCategory) {
                $xml .= '<ram:CategoryTradeTax>' .
                    '<ram:TypeCode>VAT</ram:TypeCode>' .
                    '<ram:CategoryCode>' . $allowance->vatCategory->value . '</ram:CategoryCode>' .
                    '</ram:CategoryTradeTax>';
            }
        }

        $xml .= '</ram:SpecifiedTradeAllowanceCharge>';

        return $xml;
    }
}
