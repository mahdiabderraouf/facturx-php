<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Helpers\Utils;

class GlobalIdentifiers
{
    public static function build(?array $globalIdentifiers, bool $isAtLeastBasicWl): string
    {
        if (!$globalIdentifiers) {
            return '';
        }

        $xml = '';

        if ($isAtLeastBasicWl) {
            foreach ($globalIdentifiers as $globalIdentifier) {
                $schemeId = Utils::stringOrEnumToString($globalIdentifier['schemeIdentifier']);
                $xml .= <<<XML
                <ram:GlobalID schemeID="$schemeId">{$globalIdentifier['identifier']}</ram:GlobalID>
                XML;
            }
        }

        return $xml;
    }
}
