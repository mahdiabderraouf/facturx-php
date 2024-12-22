<?php

namespace MahdiAbderraouf\FacturX\Builders;

class Identifiers
{
    public static function build(?array $identifiers): string
    {
        $xml = '';

        foreach ($identifiers ?? [] as $identifier) {
            $xml .= '<ram:ID>' . $identifier . '</ram:ID>';
        }

        return $xml;
    }
}
