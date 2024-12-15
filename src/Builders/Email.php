<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;

class Email
{
    public static function build(?string $email = null, bool $isAtLeastBasicWl = false): string
    {
        $xml = '';

        if ($isAtLeastBasicWl && $email) {
            $schemeId = SchemeIdentifier::EMAIL;
            $xml .= <<<XML
            <ram:URIUniversalCommunication>
                <ram:URIId schemeID="$schemeId">$email</ram:URIId>
            </ram:URIUniversalCommunication>
            XML;
        }

        return $xml;
    }
}
