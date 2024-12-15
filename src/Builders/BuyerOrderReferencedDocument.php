<?php

namespace MahdiAbderraouf\FacturX\Builders;

class BuyerOrderReferencedDocument
{
    public static function build(?string $purchaseOrderReference): string
    {
        if (!$purchaseOrderReference) {
            return '';
        }

        return <<<XML
        <ram:BuyerOrderReferencedDocument>
            <ram:IssuerAssignedID>{$purchaseOrderReference}</ram:IssuerAssignedID>
        </ram:BuyerOrderReferencedDocument>
        XML;
    }
}
