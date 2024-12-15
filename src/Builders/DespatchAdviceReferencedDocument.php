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
            <ram:IssuerAssignedID>$issuerAssignedID</ram:IssuerAssignedID>
        </ram:DespatchAdviceReferencedDocument>
        XML;
    }
}
