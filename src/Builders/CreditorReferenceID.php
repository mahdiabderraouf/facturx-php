<?php

namespace MahdiAbderraouf\FacturX\Builders;

class CreditorReferenceID
{
    public static function build(?string $bankAssignedCreditorIdentifier, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$bankAssignedCreditorIdentifier) {
            return '';
        }

        return '<ram:CreditorReferenceID>' . $bankAssignedCreditorIdentifier . '</ram:CreditorReferenceID>';
    }
}
