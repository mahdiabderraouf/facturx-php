<?php

namespace MahdiAbderraouf\FacturX\Builders;

class ContractReferencedDocument
{
    public static function build(?string $contactReference, bool $isAtLeastBasicWl): string
    {
        if (!$isAtLeastBasicWl || !$contactReference) {
            return '';
        }

        return <<<XML
        <ram:ContractReferencedDocument>
            <ram:IssuerAssignedID>{$contactReference}</ram:IssuerAssignedID>
        </ram:ContractReferencedDocument>
        XML;
    }
}
