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
use MahdiAbderraouf\FacturX\Enums\Unit;

$profile = Profile::BASIC;

$invoice = Invoice::createFromArray([
    // Profile and Basic Information
    'profile' => $profile,
    'number' => 'F-2024-12-15-0002',
    'typeCode' => InvoiceTypeCode::COMMERCIAL_INVOICE,
    'issueDate' => new DateTime(),

    // Invoice totals
    'totalAmountWithoutVAT' => 500.00,
    'totalVATAmount' => 100.00,
    'totalAmountWithVAT' => 600.00,
    'amountDueForPayment' => 600.00,
    'lineNetAmount' => 500.00,
    'paidAmount' => 0,

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

    // Lines
    'lines' => [
        // Line with minimum needed data
        [
            'identifier' => '001', // must be unique
            'name' => 'Product A',
            'netPrice' => 100.00,
            'totalNetPrice' => 300.00,
            'invoicedQuantity' => 3,
            'invoicedQuantityUnit' => Unit::ONE,
            'vatCategory' => VatCategory::ZERO_RATED,
        ],
        // Line with all possible data
        [
            'identifier' => '002',
            'name' => 'Product B',
            'netPrice' => 60.00,
            'totalNetPrice' => 180.00,
            'invoicedQuantity' => 3,
            'invoicedQuantityUnit' => 'XBX', // box
            'vatCategory' => VatCategory::STANDARD_RATE,
            'vatRate' => 20.00,
            'grossPrice' => 50.00,
            'priceQuantity' => 1,
            'priceQuantityUnit' => 'XBX',
            'note' => 'Dont 0,50€ d\'éco-participation',
            'standardIdentifier' => '67890',
            'schemeIdentifier' => SchemeIdentifier::GLOBAL_TRADE_ITEM_NUMBER, // default
        ],
        // Another example
        [
            'identifier' => '003',
            'name' => 'Pack of milk',
            'netPrice' => 60.00,
            'totalNetPrice' => 60.00,
            'invoicedQuantity' => 1,
            'invoicedQuantityUnit' => Unit::GROUP,
            'vatCategory' => VatCategory::STANDARD_RATE,
            'vatRate' => 20.00,
            'grossPrice' => 50.00,
            'priceQuantity' => 6, // 6 bottles per pack
            'priceQuantityUnit' => Unit::NUMBER_OF_ARTICLES,
        ],
    ],

    // Notes (comment)
    'notes' => [
        [
            'note' => 'Payment due within 30 days.',
            'noteSubjectCode' => NoteSubjectCode::GENERAL_INFORMATION, // default
        ],
        [
            'note' => 'Tout retard de paiement engendre une pénalité exigible à compter de la date d\'échéance, calculée sur la base de trois fois le taux d\'intérêt légal.',
            'noteSubjectCode' => NoteSubjectCode::PAYMENT_DETAIL,
        ],
        [
            'note' => 'Les réglements reçus avant la date d\'échéance ne donneront pas lieu à escompte.',
            'noteSubjectCode' => NoteSubjectCode::PAYMENT_TERM,
        ],
    ],

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
        'globalIdentifierSchemeIdentifier' => '0003',
        'legalRegistrationIdentifier' => 'REG-PAYEE-1001',
        'legalRegistrationSchemeIdentifier' => '0004',
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

    // Payment Terms
    'payterm' => [
        'paymentTerms' => 'Net 30',
        'dueDate' => new DateTime('2025-01-14'),
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
]);

try {
    Generator::generate(
        // path or PDF string
        '/path/to/Invoice.pdf',
        $invoice,
        // optional
        relationship: AttachmentRelationship::ALTERNATIVE,
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
