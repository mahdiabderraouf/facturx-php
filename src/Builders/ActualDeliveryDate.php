<?php

namespace MahdiAbderraouf\FacturX\Builders;

use DateTime;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class ActualDeliveryDate
{
    public static function build(?DateTime $deliveryDate, bool $isAtLeastBasicWl): string
    {
        if (!$deliveryDate || !$isAtLeastBasicWl) {
            return '';
        }

        $deliveryDate = DateFormat102::toFormat102($deliveryDate);

        return <<<XML
        <ram:ActualDeliverySupplyChainEvent>
            <ram:OccurrenceDateTime>
                <udt:DateTimeString format="102">$deliveryDate</udt:DateTimeString>
            </ram:OccurrenceDateTime>
        </ram:ActualDeliverySupplyChainEvent>
        XML;
    }
}
