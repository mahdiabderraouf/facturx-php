<?php

namespace MahdiAbderraouf\FacturX\Enums;

/**
 * Non-exhaustive list.
 */
enum PaymentMeans: string
{
    case SPECIES = '10';
    case CHECK = '20';
    case TRANSFER = '30';
    case PAYMENT_ON_BANK_ACCOUNT = '42';
    case PAYMENT_BY_CREDIT_CARD = '48';
    case DIRECT_DEBIT = '49';
    case STANDING_AGREEMENT = '57';
    case SEPA_TRANSFER = '58';
    case SEPA_DIRECT_DEBIT = '59';
    case REPORT = '97';
    case CUSTOM = 'ZZZ';
}
