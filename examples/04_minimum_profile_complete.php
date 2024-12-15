<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Models\Address;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Seller;

$profile = Profile::MINIMUM;

$invoice = new Invoice(
    $profile,
    'F-202400001',
    InvoiceTypeCode::COMMERCIAL_INVOICE,
    DateTime::createFromFormat('Y-m-d', '2024-12-02'),
    80,
    20,
    100,
    22.25,
    new Buyer(
        'Buyer NAME',
        new Address('IT'),
        // optional
        buyerReference: 'BUYER-0001',
        legalRegistrationIdentifier: '732829320',
        schemeIdentifier: SchemeIdentifier::SIREN, // default SchemeIdentifier::SIRET
    ),
    new Seller(
        'Seller NAME',
        'FR12345678901',
        new Address('FR'),
        // optional
        legalRegistrationIdentifier: '73282932000074',
        schemeIdentifier: SchemeIdentifier::SIRET, // default SchemeIdentifier::SIREN
    ),
    // optional
    businessProcessType: 'A2', // default A
    purchaseOrderReference: 'PO-202400005',
    currencyCode: 'EUR', // default EUR
    vatCurrency: 'USD', // default EUR
);

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
