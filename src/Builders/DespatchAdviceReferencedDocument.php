<?php

namespace MahdiAbderraouf\FacturX\Builders;

class DespatchAdviceReferencedDocument
{
    public static function build(?string $issuerAssignedID): string
    {
        if (!$issuerAssignedID) {
            return '';
        }

        return <<<XML
        <ram:DespatchAdviceReferencedDocument>
            <ram:IssuerAssignedID>$issuerAssignedID</ram:IssuerAssignedID>
        </ram:DespatchAdviceReferencedDocument>
        XML;
    }
}
