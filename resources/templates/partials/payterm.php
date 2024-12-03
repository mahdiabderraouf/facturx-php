<?php

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 */

if ($invoice->payterm) {
    ?>
    <ram:SpecifiedTradePaymentTerms>
        <?php
        if ($invoice->payterm->paymentTerms) {
            ?>
            <ram:Description><?= $invoice->payterm->paymentTerms; ?></ram:Description>
            <?php
        }
        if ($invoice->payterm->dueDate) {
            ?>
            <ram:DueDateDateTime>
                <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->payterm->dueDate); ?></udt:DateTimeString>
            </ram:DueDateDateTime>
            <?php
        }
        if ($invoice->payterm->mandateReferenceIdentifier) {
            ?>
            <ram:DirectDebitMandateID><?= $invoice->payterm->mandateReferenceIdentifier; ?></ram:DirectDebitMandateID>
            <?php
        }
}
?>