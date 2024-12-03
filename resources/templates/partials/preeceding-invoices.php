<?php

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

if ($isAtLeastBasicWL) {
    foreach ($invoice->precedingInvoices as $precedingInvoice) {
?>
        <ram:InvoiceReferencedDocument>
            <ram:IssuerAssignedID><?= $precedingInvoices['reference']; ?></ram:IssuerAssignedID>
            <?php
            if (isset($precedingInvoice['issueDate'])) {
            ?>
                <ram:FormattedIssueDateTime>
                    <qdt:DateTimeString format="102"><?= DateFormat102::toFormat102($precedingInvoice['issueDate']); ?></qdt:DateTimeString>
                </ram:FormattedIssueDateTime>
            <?php
            }
            ?>
        </ram:InvoiceReferencedDocument>
<?php
    }
}
