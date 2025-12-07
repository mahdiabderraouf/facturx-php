<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Payee;

class PayeeTradeParty
{
    public static function build(Profile $profile, ?Payee $payee): string
    {
        if (!$payee instanceof \MahdiAbderraouf\FacturX\Models\Payee) {
            return '';
        }

        $xml = '<ram:PayeeTradeParty>';

        $xml .= Identifiers::build([$payee->identifier]);

        $xml .= GlobalIdentifiers::build([
            [
                'identifier' => $payee->globalIdentifier,
                'schemeIdentifier' => $payee->globalIdentifierSchemeIdentifier,
            ],
        ]);

        $xml .= '<ram:Name>' . $payee->name . '</ram:Name>';

        if ($payee->legalRegistrationIdentifier) {
            $xml .= SpecifiedLegalOrganization::build(
                $profile,
                $payee->legalRegistrationIdentifier,
                $payee->legalRegistrationSchemeIdentifier
            );
        }

        return $xml . '</ram:PayeeTradeParty>';
    }
}
