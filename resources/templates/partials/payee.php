<?php

use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

if ($isAtLeastBasicWL && $invoice->payee) {
?>
    <ram:PayeeTradeParty>
        <?php
        if ($invoice->payee->identifier) {
        ?>
            <ram:ID><?= $invoice->payee->identifier; ?></ram:ID>
        <?php
        }
        if ($invoice->payee->globalIdentifier) {
        ?>
            <ram:globalID schemeID="<?= $invoice->payee->globalIdentifierSchemeIdentifier; ?>"><?= $invoice->payee->globalIdentifier; ?></ram:globalID>
        <?php
        }
        ?>
        <ram:Name><?= $invoice->payee->name; ?></ram:Name>
        <?php

        if ($invoice->payee->legalRegistrationIdentifier) {
        ?>
            <ram:SpecifiedLegalOrganization>
                <ram:ID schemeID="<?= $invoice->payee->legalRegistrationSchemeIdentifier; ?>"><?= $invoice->payee->legalRegistrationIdentifier; ?></ram:ID>
            </ram:SpecifiedLegalOrganization>
        <?php
        }
        ?>
    </ram:PayeeTradeParty>
<?php
}
