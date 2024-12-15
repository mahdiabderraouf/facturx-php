<?php

namespace MahdiAbderraouf\FacturX\Builders;

class Identifiers
{
    public static function build(?array $identifiers, bool $isAtLeastBasicWl): string
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
