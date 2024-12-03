<?php

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

if ($isAtLeastBasicWL && ($invoice->invoicingPeriodStartDate || $invoice->invoicingPeriodEndDate)) {
?>
    <ram:BillingSpecifiedPeriod>
        <?php
        if ($invoice->invoicingPeriodStartDate) {
        ?>
            ?>
            <ram:StartDateTime>
                <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->invoicingPeriodStartDate); ?></udt:DateTimeString>
            </ram:StartDateTime>
        <?php
        }

        if ($invoice->invoicingPeriodEndDate) {
        ?>
            <ram:EndDateTime>
                <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->invoicingPeriodEndDate); ?></udt:DateTimeString>
            </ram:EndDateTime>
        <?php
        }
        ?>
    </ram:BillingSpecifiedPeriod>
<?php
}
