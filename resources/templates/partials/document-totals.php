<?php

use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */
?>

<ram:SpecifiedTradeSettlementHeaderMonetarySummation>
    <?php
    if ($isAtLeastBasicWL) {
        ?>
        <ram:LineTotalAmount><?= $invoice->lineNetAmount; ?></ram:LineTotalAmount>
        <?php
        if ($invoice->chargesSum) {
            ?>
            <ram:ChargeTotalAmount><?= $invoice->chargesSum; ?></ram:ChargeTotalAmount>
            <?php
        }
        if ($invoice->allowancesSum) {
            ?>
            <ram:AllowanceTotalAmount><?= $invoice->allowancesSum; ?></ram:AllowanceTotalAmount>
            <?php
        }
    }
    ?>
    <ram:TaxBasisTotalAmount><?= $invoice->totalAmountWithoutVAT; ?></ram:TaxBasisTotalAmount>
    <ram:TaxTotalAmount currencyID="<?= $invoice->vatCurrency; ?>"><?= $invoice->totalVATAmount; ?></ram:TaxTotalAmount>
    <ram:GrandTotalAmount><?= $invoice->totalAmountWithVAT; ?></ram:GrandTotalAmount>

    <?php
    if ($isAtLeastBasicWL && $invoice->paidAmount) {
        ?>
        <ram:TotalPrepaidAmount><?= $invoice->paidAmount; ?></ram:TotalPrepaidAmount>
        <?php
    }
    ?>
    <ram:DuePayableAmount><?= $invoice->amountDueForPayment; ?></ram:DuePayableAmount>
</ram:SpecifiedTradeSettlementHeaderMonetarySummation>