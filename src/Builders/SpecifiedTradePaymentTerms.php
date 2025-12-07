<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Payterm;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class SpecifiedTradePaymentTerms
{
    public static function build(?Payterm $payterm): string
    {
        if (!$payterm instanceof \MahdiAbderraouf\FacturX\Models\Payterm) {
            return '';
        }

        $xml = '<ram:SpecifiedTradePaymentTerms>';

        if ($payterm->paymentTerms) {
            $xml .= '<ram:Description>' . $payterm->paymentTerms . '</ram:Description>';
        }

        if ($payterm->dueDate instanceof \DateTime) {
            $xml .= '<ram:DueDateDateTime>';
            $xml .= '<udt:DateTimeString format="102">'
            . DateFormat102::toFormat102($payterm->dueDate)
                . '</udt:DateTimeString>';
            $xml .= '</ram:DueDateDateTime>';
        }

        if ($payterm->mandateReferenceIdentifier instanceof \DateTime) {
            $xml .= '<ram:DirectDebitMandateID>'
            . $payterm->mandateReferenceIdentifier
                . '</ram:DirectDebitMandateID>';
        }

        return $xml . '</ram:SpecifiedTradePaymentTerms>';
    }
}
