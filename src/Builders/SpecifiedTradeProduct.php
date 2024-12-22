<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class SpecifiedTradeProduct
{
    public static function build(Line $line): string
    {
        $xml = '<ram:SpecifiedTradeProduct>';

        if ($line->standardIdentifier) {
            $xml .= GlobalIdentifiers::build([
                [
                    'identifier' => $line->standardIdentifier,
                    'schemeIdentifier' => $line->schemeIdentifier,
                ],
            ]);
        }

        $xml .= '<ram:Name>' . $line->name . '</ram:Name>';
        $xml .= '</ram:SpecifiedTradeProduct>';

        return $xml;
    }
}
