<?php

namespace MahdiAbderraouf\FacturX\Enums;

enum DeliveryLocationSchemeIdentifier: string
{
    case FACTOR = 'DL';
    case DISTRIBUTOR = 'DS';
    case MARKET_OPERATOR = 'MOP';
}
