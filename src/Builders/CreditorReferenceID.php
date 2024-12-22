<?php

namespace MahdiAbderraouf\FacturX\Builders;

class CreditorReferenceID
{
    public static function build(?string $bankAssignedCreditorIdentifier): string
    {
        if (!$bankAssignedCreditorIdentifier) {
            return '';
        }

        return '<ram:CreditorReferenceID>' . $bankAssignedCreditorIdentifier . '</ram:CreditorReferenceID>';
    }
}
