<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Models\Invoice;

$profile = Profile::MINIMUM;

$invoice = Invoice::createFromArray([
    'profile' => Profile::MINIMUM,
    'number' => 'F-202400001',
    'typeCode' => InvoiceTypeCode::COMMERCIAL_INVOICE,
    'issueDate' => DateTime::createFromFormat('Y-m-d', '2024-12-02'),
    'totalAmountWithoutVAT' => 80.00,
    'totalVATAmount' => 20.00,
    'totalAmountWithVAT' => 100.00,
    'amountDueForPayment' => 22.25,
    'buyer' => [
        'name' => 'Buyer NAME',
        'address' => [
            'countryCode' => 'FR',
        ],

        // optional
        'buyerReference' => 'BUYER-0001',
        'legalRegistrationIdentifier' => 'BUYER-SIRET',
        // Precise the legal registration scheme identifier if different from SIRET
        'schemeIdentifier' => SchemeIdentifier::SIREN,
    ],
    'seller' => [
        'name' => 'Seller NAME',
        'vatIdentifier' => 'FR12345678901',
        'address' => [
            'countryCode' => 'FR',
        ],

        // optional
        'legalRegistrationIdentifier' => 'SELLER-SIRET',
    ],

    // optional
    'businessProcessType' => 'A2', // default A1
    'purchaseOrderReference' => 'PO-202400005',
    'currencyCode' => 'EUR', // default EUR
    'vatCurrency' => 'USD', // default EUR
]);

try {
    // The only relationship allowed for minimum profile is Data which is the default one
    Generator::generate(
        // path or PDF string
        '/path/to/Invoice.pdf',
        $invoice,
        // optional
        profile: $profile, // the profile will be automatically detected when not given
        outputPath: 'Factur-X ' . $invoice->number . '.pdf', // if not given, pdf string will be returned
        additionalAttachments: [
            [
                'file' => 'extra_file.txt',
                // optional
                'filename' => 'Extra file name', // Defaults to the given file name
                'relationship' => AttachmentRelationship::SUPPLEMENT, // Defaults to AttachmentRelationship::SUPPLEMENT when not present
                'description' => 'This is some extra file description',
            ]
        ]
    );
} catch (InvalidXmlException $e) {
    $errors = $e->getErrors();
}
