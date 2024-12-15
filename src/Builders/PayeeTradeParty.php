<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Payee;

class PayeeTradeParty
{
    public static function build(?Payee $payee, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$payee) {
            return '';
        }

        $xml = '<ram:PayeeTradeParty>';

        $xml .= Identifiers::build([$payee->identifier], $isAtLeastBasicWl);

        $xml .= GlobalIdentifiers::build([
            [
                'identifier' => $payee->globalIdentifier,
                'schemeIdentifier' => $payee->globalIdentifierSchemeIdentifier,
            ],
        ], $isAtLeastBasicWl);

        $xml .= '<ram:Name>' . $payee->name . '</ram:Name>';

        if ($payee->legalRegistrationIdentifier) {
            $xml .= SpecifiedLegalOrganization::build(
                $isAtLeastBasicWl,
                $payee->legalRegistrationIdentifier,
                $payee->legalRegistrationSchemeIdentifier
            );
        }
        $xml .= '</ram:PayeeTradeParty>';

        return $xml;
    }
}
