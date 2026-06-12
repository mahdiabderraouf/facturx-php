<?php

namespace MahdiAbderraouf\FacturX\Enums;

/**
 * Non-exhaustive list.
 * ISO 6523
 */
enum SchemeIdentifier: string
{
    case SIRET = '0009';
    case SIREN = '0002';
    case EMAIL = 'EM';
    case FRCTC_ELECTRONIC_ADDRESS = '0225';
    case SWIFT = '0021';
    case DUNS = '0060';
    case GLN = '0088';
    case ODETTE = '0177';
    case GLOBAL_TRADE_ITEM_NUMBER  = '0160';
}
