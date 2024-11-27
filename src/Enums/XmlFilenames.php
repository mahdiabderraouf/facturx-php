<?php

namespace MahdiAbderraouf\FacturX\Enums;

enum XmlFilenames: string
{
    case FACTUR_X = 'factur-x.xml';

    // only for ZUGFeRD 2.0
    case ZUGFERD = 'zugferd-invoice.xml';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
