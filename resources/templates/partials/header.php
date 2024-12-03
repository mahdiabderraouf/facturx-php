<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var Profile $profile
 */
?>

<rsm:ExchangedDocumentContext>
    <ram:BusinessProcessSpecifiedDocumentContextParameter>
        <ram:ID><?= $invoice->businessProcessType; ?></ram:ID>
    </ram:BusinessProcessSpecifiedDocumentContextParameter>
    <ram:GuidelineSpecifiedDocumentContextParameter>
        <ram:ID><?= $profile->value; ?></ram:ID>
    </ram:GuidelineSpecifiedDocumentContextParameter>
</rsm:ExchangedDocumentContext>