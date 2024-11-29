<?php

namespace MahdiAbderraouf\FacturX\Enums;

use MahdiAbderraouf\FacturX\Traits\HasValues;

enum AttachmentRelationship: string
{
    use HasValues;

    case DATA = 'Data';
    case SOURCE = 'Source';
    case ALTERNATIVE = 'Alternative';
    case SUPPLEMENT = 'Supplement';
    case UNSPECIFIED = 'Unspecified';

    public function isAllowedForFacturxXml(): bool
    {
        return match ($this) {
            AttachmentRelationship::SUPPLEMENT, AttachmentRelationship::UNSPECIFIED => false,
            default => true,
        };
    }
}
