<?php

namespace MahdiAbderraouf\FacturX\Builders;

class SpecifiedTaxRegistration
{
    public static function build(?string $vatIdentifier = null): string
    {
        $xml = '';

        if ($vatIdentifier) {
            $xml .= <<<XML
            <ram:SpecifiedTaxRegistration>
                <ram:ID schemeID="VA">{$vatIdentifier}</ram:ID>
            </ram:SpecifiedTaxRegistration>
            XML;
        }

        return $xml;
    }
}
