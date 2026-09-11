<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class URIUniversalCommunication
{
    public static function build(
        ?string $electronicAddress = null,
        SchemeIdentifier|string $schemeIdentifier = SchemeIdentifier::EMAIL
    ): string {
        $xml = '';

        if ($electronicAddress) {
            $schemeId = htmlspecialchars(
                Utils::stringOrEnumToString($schemeIdentifier),
                ENT_XML1 | ENT_QUOTES,
                'UTF-8'
            );
            $xml .= <<<XML
            <ram:URIUniversalCommunication>
                <ram:URIID schemeID="{$schemeId}">{$electronicAddress}</ram:URIID>
            </ram:URIUniversalCommunication>
            XML;
        }

        return $xml;
    }
}
