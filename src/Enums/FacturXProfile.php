<?php

namespace MahdiAbderraouf\FacturX\Enums;

use MahdiAbderraouf\FacturX\Traits\HasValues;

enum FacturXProfile: string
{
    use HasValues;

    case MINIMUM = 'urn:factur-x.eu:1p0:minimum';
    case BASIC_WL = 'urn:factur-x.eu:1p0:basicwl';
    case BASIC = 'urn:factur-x.eu:1p0:basic';
    case EN16931 = 'urn:cen.eu:en16931:2017';
    case EXTENDED = 'urn:cen.eu:en16931:2017#conformant#urn:factur-x.eu:1p0:extended';
}
