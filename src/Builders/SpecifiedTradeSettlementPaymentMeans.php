<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Payment;

class SpecifiedTradeSettlementPaymentMeans
{
    public static function build(?Payment $payment): string
    {
        if (!$payment instanceof \MahdiAbderraouf\FacturX\Models\Payment) {
            return '';
        }

        $xml = '<ram:SpecifiedTradeSettlementPaymentMeans>';
        $xml .= '<ram:TypeCode>' . $payment->paymentMeansTypeCode . '</ram:TypeCode>';

        if ($payment->debitedAccountIdentifier) {
            $xml .= <<<XML
            <ram:PayerPartyDebtorFinancialAccount>
                <ram:IBANID>$payment->debitedAccountIdentifier</ram:IBANID>
            </ram:PayerPartyDebtorFinancialAccount>
            XML;
        }

        if ($payment->paymentAccountIdentifier || $payment->nationalAccountNumber) {
            $xml .= '<ram:PayeePartyCreditorFinancialAccount>';
            if ($payment->paymentAccountIdentifier) {
                $xml .= '<ram:IBANID>' . $payment->paymentAccountIdentifier . '</ram:IBANID>';
            }

            if ($payment->nationalAccountNumber) {
                $xml .= '<ram:ProprietaryID>' . $payment->nationalAccountNumber . '</ram:ProprietaryID>';
            }

            $xml .= '</ram:PayeePartyCreditorFinancialAccount>';
        }

        return $xml . '</ram:SpecifiedTradeSettlementPaymentMeans>';
    }
}
