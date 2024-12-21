<?php

namespace MahdiAbderraouf\FacturX\Enums;

enum NoteSubjectCode: string
{
    case GENERAL_INFORMATION = 'AAI';
    case SUPPLIER_NOTES = 'SUR';
    case REGULATORY_INFORMATION = 'REG';
    case LEGAL_INFORMATION = 'ABL';
    case TAX_INFORMATION = 'TXD';
    case CUSTOMS_INFORMATION = 'CUS';
    case PAYMENT_DETAIL = 'PMD';
    case PAYMENT_TERM = 'AAB';
}
