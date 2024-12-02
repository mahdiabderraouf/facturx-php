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
    case SWIFT = '0021';
    case DUNS = '0060';
    case GLN = '0088';
    case ODETTE = '0177';
}
