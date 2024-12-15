<?php

namespace MahdiAbderraouf\FacturX\Builders;

class DespatchAdviceReferencedDocument
{
    public static function build(?string $issuerAssignedID, bool $isAtLeastBasicWl): string
    {
        if (!$issuerAssignedID || !$isAtLeastBasicWl) {
            return '';
        }

        return <<<XML
        <ram:DespatchAdviceReferencedDocument>
            <udt:IssuerAssignedID>$issuerAssignedID</udt:IssuerAssignedID>
        </ram:DespatchAdviceReferencedDocument>
        XML;
    }
}
