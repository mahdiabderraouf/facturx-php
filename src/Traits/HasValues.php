<?php

namespace MahdiAbderraouf\FacturX\Traits;

trait HasValues
{
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
