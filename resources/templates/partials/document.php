<?php

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */
?>

<rsm:ExchangedDocument>
    <ram:ID><?= $invoice->number; ?></ram:ID>
    <ram:TypeCode><?= $invoice->typeCode; ?></ram:TypeCode>
    <ram:IssueDateTime>
        <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->issueDate); ?></udt:DateTimeString>
    </ram:IssueDateTime>
    <?php
    if ($isAtLeastBasicWL && $invoice->note) {
        ?>
        <ram:IncludedNote>
            <ram:Content><?= $invoice->note; ?></ram:Content>
            <ram:SubjectCode><?= $invoice->noteSubjectCode; ?></ram:SubjectCode>
        </ram:IncludedNote>
        <?php
    }
    ?>
</rsm:ExchangedDocument>