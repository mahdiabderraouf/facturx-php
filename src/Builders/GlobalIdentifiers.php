<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Helpers\Utils;

class GlobalIdentifiers
{
    public static function build(?array $globalIdentifiers): string
    {
        $xml = '';

        foreach ($globalIdentifiers ?? [] as $globalIdentifier) {
            if (!$globalIdentifier['identifier']) {
                continue;
            }

            $schemeId = Utils::stringOrEnumToString($globalIdentifier['schemeIdentifier']);
            $xml .= <<<XML
            <ram:GlobalID schemeID="{$schemeId}">{$globalIdentifier['identifier']}</ram:GlobalID>
            XML;
        }

        return $xml;
    }
}
