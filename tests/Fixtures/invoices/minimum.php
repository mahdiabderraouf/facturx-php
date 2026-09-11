<?php

use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;

return [
    'profile' => Profile::MINIMUM,
    'number' => 'F-202400001',
    'typeCode' => InvoiceTypeCode::COMMERCIAL_INVOICE,
    'issueDate' => new DateTime('2024-12-02'),
    'totalAmountWithoutVAT' => 80.00,
    'totalVATAmount' => 20.00,
    'totalAmountWithVAT' => 100.00,
    'amountDueForPayment' => 22.25,
    'buyer' => [
        'name' => 'Buyer NAME',
        'address' => ['countryCode' => 'FR'],
        'buyerReference' => 'BUYER-0001',
        'legalRegistrationIdentifier' => 'BUYER-SIRET',
        'schemeIdentifier' => SchemeIdentifier::SIREN,
    ],
    'seller' => [
        'name' => 'Seller NAME',
        'vatIdentifier' => 'FR12345678901',
        'address' => ['countryCode' => 'FR'],
        'legalRegistrationIdentifier' => 'SELLER-SIRET',
    ],
    'businessProcessType' => 'A2',
    'purchaseOrderReference' => 'PO-202400005',
    'currencyCode' => 'EUR',
];
