<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class InvoiceReferencedDocument
{
    public static function build(?array $precedingInvoices, bool $isAtLeastBasicWl): string
    {
        $xml = '';

        if ($isAtLeastBasicWl) {
            foreach ($precedingInvoices ?? [] as $precedingInvoice) {
                $xml .= '<ram:InvoiceReferencedDocument>';
                $xml .= '<ram:IssuerAssignedID>' . $precedingInvoice['reference'] . '</ram:IssuerAssignedID>';

                if (isset($precedingInvoice['issueDate'])) {
                    $xml .= '<ram:FormattedIssueDateTime>';
                    $xml .= '<qdt:DateTimeString format="102">'
                        . DateFormat102::toFormat102($precedingInvoice['issueDate'])
                        . '</qdt:DateTimeString>';
                    $xml .= '</ram:FormattedIssueDateTime>';
                }

                $xml .= '</ram:InvoiceReferencedDocument>';
            }
        }

        return $xml;
    }
}
