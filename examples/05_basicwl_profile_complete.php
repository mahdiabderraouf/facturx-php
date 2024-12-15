<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\NoteSubjectCode;
use MahdiAbderraouf\FacturX\Enums\PaymentMeans;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Models\Address;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Payee;
use MahdiAbderraouf\FacturX\Models\Payment;
use MahdiAbderraouf\FacturX\Models\Payterm;
use MahdiAbderraouf\FacturX\Models\Seller;
use MahdiAbderraouf\FacturX\Models\TaxRespresentative;
use MahdiAbderraouf\FacturX\Models\VatBreakdown;

$profile = Profile::BASIC_WL;

$invoice = new Invoice(
    $profile,
    'F-2024-12-15-0001',
    InvoiceTypeCode::COMMERCIAL_INVOICE,
    new DateTime(),
    80,
    20,
    100,
    50,
    new Buyer(
        'Buyer name',
        new Address('FR', '83210', '25 rue de test', 'Bat 7'),
        'buyer@email.com',
        legalRegistrationIdentifier: 'SIRET-BUYER',
        identifiers: ['B-2514', 'EXT-2541'],
        globalIndetifiers: [
            [
                'identifier' => 'GLOBAL-IDENTIGIER-BUYER',
                'schemeIdentifier' => SchemeIdentifier::SIREN
            ],
            // other global identifiers
        ],
        vatIdentifier: 'FR21514164516451',
        buyerReference: 'REF-125',
        accountingReference: 'ACCOUNTING-REF'
    ),
    new Seller(
        'Seller name',
        'FR2151481365',
        new Address('FR', '83210', '25 rue de test', 'Bat 7'),
        'seller@email.com',
        legalRegistrationIdentifier: 'SIRET-SELLER',
        identifiers: ['S-1518'],
        globalIndetifiers: [
            [
                'identifier' => 'GLOBAL-IDENTIGIER-SELLER',
                'schemeIdentifier' => SchemeIdentifier::SIREN
            ],
            // other global identifiers
        ],
        tradingName: 'SELLER TRADING NAME',
        taxRespresentative: new TaxRespresentative('Tax representative name', 'FR232554', new Address('FR')),
    ),
    lineNetAmount: 80,
    paidAmount: 50.00,
    purchaseOrderReference: 'PO-2024000015',
    contractReference: 'contract reference',
    note: 'This is a comment',
    noteSubjectCode: NoteSubjectCode::GENERAL_INFORMATION,
    bankAssignedCreditorIdentifier: 'creditor id',
    remittanceInformation: 'payment ref',
    vatAccountingCurrencyCode: 'EUR',
    payee: new Payee('Payee name'),
    payment: new Payment(PaymentMeans::SPECIES, 'account id'),
    vatBreakdowns: [new VatBreakdown(15, 80, VatCategory::STANDARD_RATE, 20.00)],
    invoicingPeriodStartDate: new DateTime('2024-01-01'),
    invoicingPeriodEndDate: new DateTime('2024-12-31'),
    payterm: new Payterm('custom', new DateTime('2024-12-22')),
    precedingInvoices: [
        [
            'reference' => 'F-2024-11-15-0001',
            'issueDate' => new DateTime('2024-11-15')
        ],
        // older preceding invoices
    ]
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
                'relationship' => AttachmentRelationship::SUPPLEMENT, // Defaults to AttachmentRelationship::UNSPECIFIED when not present
                'description' => 'This is some extra file description',
            ]
        ]
    );
} catch (InvalidXmlException $e) {
    $errors = $e->getErrors();
}
