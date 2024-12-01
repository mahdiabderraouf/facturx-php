<?php

namespace MahdiAbderraouf\FacturX;

use MahdiAbderraouf\FacturX\Models\Invoice;

class Builder
{
    public static function build(Invoice $invoice): string
    {
        $seller = $invoice->seller;
        $buyer = $invoice->buyer;
        $profile = $invoice->profile;

        ob_start();
        include __DIR__ . '/../resources/templates/factur-x.php';
        $xml = ob_get_clean();

        return '<?xml version="1.0" encoding="UTF-8"?>' . $xml;
    }
}
