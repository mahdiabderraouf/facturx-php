<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Line;

class AssociatedDocumentLineDocument
{
    public static function build(Line $line): string
    {
        $xml = '<ram:AssociatedDocumentLineDocument>';

        $xml .= '<ram:LineID>' . $line->identifier . '</ram:LineID>';

        if ($line->note) {
            $xml .= '<ram:IncludedNote><ram:Content>' . $line->note . '</ram:Content></ram:IncludedNote>';
        }

        return $xml . '</ram:AssociatedDocumentLineDocument>';
    }
}
