<?php

namespace MahdiAbderraouf\FacturX\Builders;

use DateTime;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class BillingSpecifiedPeriod
{
    public static function build(
        ?DateTime $invoicingPeriodStartDate,
        ?DateTime $invoicingPeriodEndDate
    ): string {
        if (!$invoicingPeriodStartDate && !$invoicingPeriodEndDate) {
            return '';
        }

        $xml = '<ram:BillingSpecifiedPeriod>';

        if ($invoicingPeriodStartDate instanceof \DateTime) {
            $xml .= '<ram:StartDateTime>';
            $xml .= '<udt:DateTimeString format="102">' . DateFormat102::toFormat102($invoicingPeriodStartDate) . '</udt:DateTimeString>';
            $xml .= '</ram:StartDateTime>';
        }

        if ($invoicingPeriodEndDate instanceof \DateTime) {
            $xml .= '<ram:EndDateTime>';
            $xml .= '<udt:DateTimeString format="102">' . DateFormat102::toFormat102($invoicingPeriodEndDate) . '</udt:DateTimeString>';
            $xml .= '</ram:EndDateTime>';
        }

        return $xml . '</ram:BillingSpecifiedPeriod>';
    }
}
