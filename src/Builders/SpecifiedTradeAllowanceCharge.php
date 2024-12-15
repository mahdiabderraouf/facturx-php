<?php

namespace MahdiAbderraouf\FacturX\Builders;

class SpecifiedTradeAllowanceCharge
{
    public static function build(?array $allowances, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$allowances) {
            return '';
        }

        $xml = '<ram:SpecifiedTradeAllowanceCharge>';

        foreach ($allowances as $allowance) {
            $xml .= '<ram:ChargeIndicator>';
            $xml .= '<udt:Indicator>' . (!$allowance->isAllowance) . '</udt:Indicator>';

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

            $xml .= '</ram:ChargeIndicator>';
        }

        $xml .= '</ram:SpecifiedTradeAllowanceCharge>';

        return $xml;
    }
}
