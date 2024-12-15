<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ReceivableSpecifiedTradeAccountingAccount
{
    public static function build(?string $buyerAccountingReference, bool $isAtLeastBasicWl): string
    {
        $xml = '';

        if ($isAtLeastBasicWl && $buyerAccountingReference) {
            $xml .= '<ram:ReceivableSpecifiedTradeAccountingAccount>' .
                '<ram:ID>' . $buyerAccountingReference . '</ram:ID>' .
                '</ram:ReceivableSpecifiedTradeAccountingAccount>';
        }

        return $xml;
    }
}
