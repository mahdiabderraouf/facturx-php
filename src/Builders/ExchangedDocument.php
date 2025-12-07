<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

class ExchangedDocument
{
    public static function build(Invoice $invoice): string
    {
        $issueDate = DateFormat102::toFormat102($invoice->issueDate);

        $includedNote = '';
        if ($invoice->profile->isAtLeast(Profile::BASIC_WL) && $invoice->notes) {
            foreach ($invoice->notes as $note) {
                $includedNote .= <<<XML
                <ram:IncludedNote>
                    <ram:Content>{$note->note}</ram:Content>
                    <ram:SubjectCode>{$note->noteSubjectCode->value}</ram:SubjectCode>
                </ram:IncludedNote>
                XML;
            }
        }

        return <<<XML
        <rsm:ExchangedDocument>
            <ram:ID>{$invoice->number}</ram:ID>
            <ram:TypeCode>{$invoice->typeCode}</ram:TypeCode>
            <ram:IssueDateTime>
                <udt:DateTimeString format="102">{$issueDate}</udt:DateTimeString>
            </ram:IssueDateTime>
            {$includedNote}
        </rsm:ExchangedDocument>
        XML;
    }
}
