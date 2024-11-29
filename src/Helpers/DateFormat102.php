<?php

namespace MahdiAbderraouf\FacturX\Helpers;

use DateTime;
use DateTimeInterface;

class DateFormat102
{
    public const FORMAT = 'Ymd';

    public static function toFormat102(DateTimeInterface $date): string
    {
        return $date->format(self::FORMAT);
    }

    public static function fromFormat102(string $dateString): DateTimeInterface
    {
        return DateTime::createFromFormat(self::FORMAT, $dateString)->setTime(0, 0);
    }
}