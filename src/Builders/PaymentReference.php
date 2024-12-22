<?php

namespace MahdiAbderraouf\FacturX\Builders;

class PaymentReference
{
    public static function build(?string $remittanceInformation): string
    {
        if (!$remittanceInformation) {
            return '';
        }

        return '<ram:PaymentReference>' . $remittanceInformation . '</ram:PaymentReference>';
    }
}
