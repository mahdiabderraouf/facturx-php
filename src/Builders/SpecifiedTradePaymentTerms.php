<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Payterm;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class SpecifiedTradePaymentTerms
{
    public static function build(?Payterm $payterm): string
    {
        if (!$payterm) {
            return '';
        }

        $xml = '<ram:SpecifiedTradePaymentTerms>';

        if ($payterm->paymentTerms) {
            $xml .= '<ram:Description>' . $payterm->paymentTerms . '</ram:Description>';
        }

        if ($payterm->dueDate) {
            $xml .= '<ram:DueDateDateTime>';
            $xml .= '<udt:DateTimeString format="102">'
            . DateFormat102::toFormat102($payterm->dueDate)
                . '</udt:DateTimeString>';
            $xml .= '</ram:DueDateDateTime>';
        }

        if ($payterm->mandateReferenceIdentifier) {
            $xml .= '<ram:DirectDebitMandateID>'
            . $payterm->mandateReferenceIdentifier
                . '</ram:DirectDebitMandateID>';
        }

        $xml .= '</ram:SpecifiedTradePaymentTerms>';

        return $xml;
    }
}
