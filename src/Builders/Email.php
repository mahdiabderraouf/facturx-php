<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;

class Email
{
    public static function build(bool $isAtLeastBasicWl, ?string $email = null): string
    {
        $xml = '';

        if ($isAtLeastBasicWl && $email) {
            $schemeId = SchemeIdentifier::EMAIL->value;
            $xml .= <<<XML
            <ram:URIUniversalCommunication>
                <ram:URIID schemeID="$schemeId">$email</ram:URIID>
            </ram:URIUniversalCommunication>
            XML;
        }

        return $xml;
    }
}
