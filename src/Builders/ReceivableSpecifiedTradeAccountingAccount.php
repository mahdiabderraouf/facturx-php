<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ReceivableSpecifiedTradeAccountingAccount
{
    public static function build(?string $buyerAccountingReference): string
    {
        if (!$buyerAccountingReference) {
            return '';
        }

        return '<ram:ReceivableSpecifiedTradeAccountingAccount>' .
        '<ram:ID>' . $buyerAccountingReference . '</ram:ID>' .
        '</ram:ReceivableSpecifiedTradeAccountingAccount>';
    }
}
