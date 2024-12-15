<?php

namespace MahdiAbderraouf\FacturX\Builders;

class PaymentReference
{
    public static function build(?string $remittanceInformation, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$remittanceInformation) {
            return '';
        }

        return '<ram:PaymentReference>' . $remittanceInformation . '</ram:PaymentReference>';
    }
}
