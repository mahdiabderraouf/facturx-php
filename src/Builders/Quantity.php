<?php

namespace MahdiAbderraouf\FacturX\Builders;

class Quantity
{
    public static function build(string $tagName, ?float $quantity, ?string $unit): string
    {
        if ($quantity === null) {
            $unitCode = $unit ? ' unitCode="' . ($unit ?? '') . '"' : '';
            return '<ram:' . $tagName . $unitCode . '>' . $quantity . '</ram:' . $tagName . '>';
        }
    }
}
