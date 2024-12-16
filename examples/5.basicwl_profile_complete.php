<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Enums\VatCategory;
use MahdiAbderraouf\FacturX\Enums\PaymentMeans;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\NoteSubjectCode;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Enums\DeliveryLocationSchemeIdentifier;

$profile = Profile::BASIC_WL;

$invoice = Invoice::createFromArray([
    // Profile and Basic Information
    'profile' => Profile::BASIC_WL,
    'number' => 'F-2024-12-15-0002',
    'typeCode' => InvoiceTypeCode::COMMERCIAL_INVOICE,
    'issueDate' => new DateTime(),

    // Invoice totals
    'totalAmountWithoutVAT' => 500.00,
    'totalVATAmount' => 100.00,
    'totalAmountWithVAT' => 600.00,
    'amountDueForPayment' => 600.00,
    'lineNetAmount' => 500.00,
    'paidAmount' => 600.00,

    // Buyer Information
    'buyer' => [
        'name' => 'John Doe Inc.',
        'address' => [
            'countryCode' => 'FR',
            'postCode' => '75001',
            'address1' => '1 Rue de Paris',
            'city' => 'Paris',
            'province' => 'Ile-de-France',
        ],
        'email' => 'johndoe@email.com',
        'legalRegistrationIdentifier' => 'SIRET-BUYER-12345',
        'identifiers' => ['B-1001'],
        'globalIdentifiers' => [
            [
                'identifier' => 'GLOBAL-IDENTIFIER-BUYER',
                'schemeIdentifier' => SchemeIdentifier::SIREN
            ]
        ],
        'vatIdentifier' => 'FR21514164516451',
        'buyerReference' => 'BUYER-REF-1001',
        'accountingReference' => 'ACCT-1001'
    ],

    // Seller Information
    'seller' => [
        'name' => 'ACME Corp.',
        'vatIdentifier' => 'FR2151481365',
        'address' => [
            'countryCode' => 'FR',
            'postCode' => '69001',
            'address1' => '5 Rue des Alpes',
            'city' => 'Lyon',
            'province' => 'Auvergne-Rhône-Alpes',
        ],
        'email' => 'sales@acmecorp.com',
        'legalRegistrationIdentifier' => 'SIRET-SELLER-67890',
        'identifiers' => ['S-2001'],
        'globalIdentifiers' => [
            [
                'identifier' => 'GLOBAL-IDENTIFIER-SELLER',
                'schemeIdentifier' => SchemeIdentifier::SIREN
            ]
        ],
        'tradingName' => 'ACME CORPORATION',
        'taxRespresentative' => [
            'name' => 'Tax Representative',
            'vatIdentifier' => 'FR232554',
            'address' => [
                'countryCode' => 'FR',
                'postCode' => '75001',
                'address1' => '1 Rue de Paris',
                'city' => 'Paris',
                'province' => 'Ile-de-France',
            ]
        ]
    ],

    // Note (comment)
    'note' => 'Payment due within 30 days.',
    'noteSubjectCode' => NoteSubjectCode::GENERAL_INFORMATION,

    'purchaseOrderReference' => 'PO-2024000111',

    'contractReference' => 'Contract-001',

    'vatAccountingCurrencyCode' => 'USD',
    'totalVATAmountInAccountingCurrency' => 10.00,

    // Payee and Payment Information
    'bankAssignedCreditorIdentifier' => 'BANK-12345',
    'remittanceInformation' => 'Invoice F-2024-12-15-0002',
    'payee' => [
        'name' => 'ACME Corp. Payments',
        'identifier' => 'PAYEE-1001',
        'globalIdentifier' => 'GLOBAL-PAYEE-ID',
        'globalIdentifierSchemeIdentifier' => '003',
        'legalRegistrationIdentifier' => 'REG-PAYEE-1001',
        'legalRegistrationSchemeIdentifier' => '004',
    ],
    'payment' => [
        'paymentMeansTypeCode' => PaymentMeans::CHECK,
        'debitedAccountIdentifier' => 'account-12345',
        'paymentAccountIdentifier' => 'payment-67890',
        'nationalAccountNumber' => '9876543210',
    ],

    // VAT Breakdown
    'vatBreakdowns' => [
        [
            'vatCategoryTaxAmount' => 100.00,
            'vatCategoryTaxableAmount' => 500.00,
            'vatCategory' => VatCategory::STANDARD_RATE,
            'percentage' => 20.00,
        ]
    ],

    // Invoicing Period
    'invoicingPeriodStartDate' => new DateTime('2024-01-01'),
    'invoicingPeriodEndDate' => new DateTime('2024-12-31'),

    // Payment Terms
    'payterm' => [
        'paymentTerms' => 'Net 30',
        'dueDate' => new DateTime('2025-01-14'),
    ],

    // Preceding Invoices
    'precedingInvoices' => [
        [
            'reference' => 'F-2024-11-15-0001',
            'issueDate' => new DateTime('2024-11-15')
        ]
    ],

    // Charges
    'charges' => [
        [
            'amount' => 50.00,
            'vatCategory' => VatCategory::STANDARD_RATE,
            'vatRate' => 5.5,
            'reasonCode' => '88',
            'reason' => 'Material surcharge/deduction'
        ]
    ],

    // Allowances
    'allowances' => [
        [
            'amount' => 20.00,
            'vatCategory' => VatCategory::STANDARD_RATE,
            'percentage' => 5.00,
            'reasonCode' => '95',
            'reason' => 'Discount'
        ]
    ],

    // Delivery Information
    'delivery' => [
        'locationIdentifier' => 'LOC-1234',
        'locationGlobalIdentifier' => 'GLOBAL-LOC-5678',
        'locationSchemeIdentifier' => DeliveryLocationSchemeIdentifier::DISTRIBUTOR,
        'partyName' => 'John Doe Inc.',
        'address' => [
            'countryCode' => 'FR',
            'postCode' => '75001',
            'address1' => '1 Rue de Paris',
            'city' => 'Paris',
            'province' => 'Ile-de-France',
        ],
        'actualDeliveryDate' => new DateTime('2024-12-10'),
        'issuerAssignedID' => 'ISSUER-12345'
    ]
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
                'relationship' => AttachmentRelationship::SUPPLEMENT, // Defaults to AttachmentRelationship::UNSPECIFIED when not present
                'description' => 'This is some extra file description',
            ]
        ]
    );
} catch (InvalidXmlException $e) {
    $errors = $e->getErrors();
}
