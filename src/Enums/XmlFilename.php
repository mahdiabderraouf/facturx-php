<?php

namespace MahdiAbderraouf\FacturX\Enums;

use MahdiAbderraouf\FacturX\Traits\HasValues;

enum XmlFilename: string
{
    use HasValues;

    case FACTUR_X = 'factur-x.xml';

    // only for ZUGFeRD 2.0
    case ZUGFERD = 'zugferd-invoice.xml';
}
