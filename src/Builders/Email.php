<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Email
{
    public static function build(
        ?string $email = null,
        SchemeIdentifier|string $schemeIdentifier = SchemeIdentifier::EMAIL
    ): string {
        $xml = '';

        if ($email) {
            $schemeId = htmlspecialchars(
                Utils::stringOrEnumToString($schemeIdentifier),
                ENT_XML1 | ENT_QUOTES,
                'UTF-8'
            );
            $xml .= <<<XML
            <ram:URIUniversalCommunication>
                <ram:URIID schemeID="{$schemeId}">{$email}</ram:URIID>
            </ram:URIUniversalCommunication>
            XML;
        }

        return $xml;
    }
}
