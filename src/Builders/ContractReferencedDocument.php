<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ContractReferencedDocument
{
    public static function build(?string $contactReference): string
    {
        if (!$contactReference) {
            return '';
        }

        return <<<XML
        <ram:ContractReferencedDocument>
            <ram:IssuerAssignedID>{$contactReference}</ram:IssuerAssignedID>
        </ram:ContractReferencedDocument>
        XML;
    }
}
