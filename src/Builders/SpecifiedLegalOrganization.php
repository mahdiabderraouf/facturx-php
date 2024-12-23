<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;

class SpecifiedLegalOrganization
{
    public static function build(
        Profile $profile,
        ?string $legalRegistrationIdentifier = null,
        ?string $schemeIdentifier = null,
        ?string $tradingName = null,
    ): string {
        $xml = '';

        $addTradingName = $tradingName && $profile->isAtLeast(Profile::BASIC_WL);

        if ($legalRegistrationIdentifier || $addTradingName) {
            $xml .= '<ram:SpecifiedLegalOrganization>';

            if ($legalRegistrationIdentifier) {
                $xml .= <<<XML
                <ram:ID schemeID="{$schemeIdentifier}">{$legalRegistrationIdentifier}</ram:ID>
                XML;
            }
            if ($addTradingName) {
                $xml .= <<<XML
                <ram:TradingBusinessName>{$tradingName}</ram:TradingBusinessName>
                XML;
            }

            $xml .= '</ram:SpecifiedLegalOrganization>';
        }

        return $xml;
    }
}
