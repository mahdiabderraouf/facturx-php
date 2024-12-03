<?php

use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

if ($isAtLeastBasicWL && $invoice->payment) {
?>
    <ram:SpecifiedTradeSettlementPaymentMeans>
        <ram:TypeCode><?= $invoice->payment->paymentMeansTypeCode; ?></ram:TypeCode>
        <?php
        if ($invoice->payment->debitedAccountIdentifier) {
        ?>
            <ram:PayerPartyDebtorFinancialAccount>
                <ram:IBANID><?= $invoice->payment->paymentMeansTypeCode; ?></ram:IBANID>
            </ram:PayerPartyDebtorFinancialAccount>
        <?php
        }
        if ($invoice->payment->paymentAccountIdentifier || $invoice->payment->nationalAccountNumber) {
        ?>
            <ram:PayeePartyCreditorFinancialAccount>
                <?php

                if ($invoice->payment->debitedAccountIdentifier) {
                ?>
                    <ram:IBANID><?= $invoice->payment->debitedAccountIdentifier; ?></ram:IBANID>
                <?php
                }
                if ($invoice->payment->nationalAccountNumber) {
                ?>
                    <ram:ProprietaryID><?= $invoice->payment->nationalAccountNumber; ?></ram:ProprietaryID>
                <?php
                }
                ?>
            </ram:PayeePartyCreditorFinancialAccount>
        <?php
        }
        ?>
    </ram:SpecifiedTradeSettlementPaymentMeans>
<?php
}
