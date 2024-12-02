<?php

namespace MahdiAbderraouf\FacturX\Enums;

/**
 * Non-exhaustive list.
 */
enum VatCategory: string
{
    case STANDARD_RATE = 'S';
    case ZERO_RATED = 'Z';
    case EXEMPT = 'E';
    case REVERSE_CHARGE = 'AE';
    case INTRA_COMMUNITY = 'K';
    case EXPORT_EXEMPT = 'G';
    case OUTSIDE_SCOPE = 'O';
    case CANARY_ISLANDS = 'L';
    case CEUTA_AND_MELILLA = 'M';
}
