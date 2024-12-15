<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ReceivableSpecifiedTradeAccountingAccount
{
    public static function build(?string $buyerAccountingReference, bool $isAtLeastBasicWl): string
    {
        $xml = '';

        if ($isAtLeastBasicWl && $buyerAccountingReference) {
            $xml .= '<ram:ReceivableSpecifiedTradeAccountingAccount>';
            $xml .= $buyerAccountingReference;
            $xml .= '</ram:ReceivableSpecifiedTradeAccountingAccount>';
        }

        return $xml;
    }
}
