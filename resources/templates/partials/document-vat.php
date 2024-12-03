<?php

use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

if ($isAtLeastBasicWL && $invoice->vatBreakdowns) {
    ?>
    <ram:ApplicableTradeTax>
        <?php
        foreach ($invoice->vatBreakdowns as $vatBreakdown) {
            ?>
            <ram:CalculatedAmount><?= $vatBreakdown->vatCategoryTaxAmount; ?></ram:CalculatedAmount>
            <ram:TypeCode>VAT</ram:TypeCode>

            <?php
            if ($vatBreakdown->exemptionReason) {
                ?>
                <ram:ExemptionReason><?= $vatBreakdown->exemptionReason; ?></ram:ExemptionReason>
                <?php
            }
            ?>
            <ram:BasisAmount><?= $vatBreakdown->vatCategoryTaxableAmount; ?></ram:BasisAmount>
            <ram:CategoryCode><?= $vatBreakdown->vatCategory->value; ?></ram:CategoryCode>
            <?php
            if ($vatBreakdown->vatExemptionReasonCode) {
                ?>
                <ram:ExemptionReasonCode><?= $vatBreakdown->vatExemptionReasonCode; ?></ram:ExemptionReasonCode>
                <?php
            }
            if ($vatBreakdown->valueAddedTaxPointDateCode) {
                ?>
                <ram:DueDateTypeCode><?= $vatBreakdown->valueAddedTaxPointDateCode; ?></ram:DueDateTypeCode>
                <?php
            }
            if ($vatBreakdown->percentage) {
                ?>
                <ram:RateApplicablePercent><?= $vatBreakdown->percentage; ?></ram:RateApplicablePercent>
                <?php
            }
        }
        ?>
    </ram:ApplicableTradeTax>
    <?php
}
