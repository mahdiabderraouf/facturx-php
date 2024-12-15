<?php

namespace MahdiAbderraouf\FacturX\Builders;

class Identifiers
{
    public static function build(?array $identifiers, bool $isAtLeastBasicWl = false): string
    {
        if (!$identifiers) {
            return '';
        }

        $xml = '';

        if ($isAtLeastBasicWl) {
            foreach ($identifiers as $identifier) {
                $xml .= <<<XML
                <ram:ID>$identifier</ram:ID>
                XML;
            }
        }

        return $xml;
    }
}
