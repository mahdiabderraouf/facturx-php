# Factur-X PHP
A PHP library for managing Factur-x/ZUGFeRD compliant PDF invoices.

## Table of Contents
- [Factur-X PHP](#factur-x-php)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Requirements](#requirements)
  - [Installation](#installation)
    - [Using composer](#using-composer)
  - [Documentation](#documentation)
  - [Usage](#usage)
    - [Generate minimum profile XML](#generate-minimum-profile-xml)
    - [Validate XML against XSD](#validate-xml-against-xsd)
    - [Generate a Factur-X PDF](#generate-a-factur-x-pdf)
    - [Parse Factur-X PDF](#parse-factur-x-pdf)
  - [Bug reports](#bug-reports)
  - [Contributions](#contributions)
  - [Roadmap](#roadmap)

---

## Features
- **XML generation:** Generate Factur-X XML file from an `Invoice` object, supports `minimum`, `basicwl` and `basic` profiles.
- **PDF generation:** Generate PDF-A3b Factur-X invoice from a given PDF file and a generated/provided XML file.
- **XML validation:** Validates Factur-X XML against the offical Extension Schema Definition (XSD).
- **Parsing:** Extract XML file from a Factur-X.

---

## Requirements
- PHP version: `>= 8.2`
- [poppler-utils](https://tracker.debian.org/pkg/poppler) for XML extraction

---

## Installation

### Using composer
```bash
composer require mahdiabderraouf/facturx-php
```

---

## Documentation
The full documentation can be found [here](../../wiki).

---

## Usage
Here are some quick examples of usage. For advanced usage please refer to the [documentation](../../wiki)

### Generate minimum profile XML
```php
use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

// Create an invoice object from an array or you can use constructor instead (new Invoice (...))
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

$xml = $invoice->toXml();
// Or
$xml = Builder::build($invoice);
```

### Validate XML against XSD
Validate Factur-X XML against XSD. The XML source can be either a PDF file path, an XML file path or an XML string.
```php
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Validator;

$profile = Profile::MINIMUM;

// PDF path, XML path or XML string
$source = '/path/to/invoice.pdf';

try {
    Validator::validate(
        $source,

        // Validate against a specific profile, or auto-detect it from the XML.
        Profile::EN16931,
    );
} catch (UnableToExtractXmlException $e) {
    // Failed to extract XML from given PDF
    $message = $e->getMessage();
} catch (InvalidXmlException $e) {
    // array of LibXMLError
    $errors = $e->getErrors();
}

// Or simply check if its valid or not
$isValid = Validator::isValid($source);
```

### Generate a Factur-X PDF
Using the static method `Generator::generate` you can embed an XML into a PDF file to generate a Factur-X file.

To ensure the integrity of every Factur-X file, the XML is validated before being embedded, so there is no need to validate it beforehand.

Please note about attachment relationship:
- The only relationships that can be used for the XML file are `Data`, `Source` and `Alternative`.
- In Germany the only relationship allowed is `Alternative`.
- For profiles `minimum` and `basicwl` only the relationship `Data` is allowed.
- It is not recommanded to use the relationship `UNSPECIFIED` when adding additional attachments.

If you are generating invoices in Germany, the profiles `minimum` and `basicwl` are not considered legally as an invoice since they don't contain enough information. Same rule will be applied in France in the future so you should be using at least the `basic` profile.
```php
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Generator;

$profile = Profile::BASIC;
$invoice = Invoice::createFromArray([...]);

try {
    $pdfString = Generator::generate(
        // path or PDF string
        '/path/to/Invoice.pdf',
        // Invoice, XML string or path
        $invoice,

        // optional
        AttachmentRelationship::DATA, // default one
        'outputPath.pdf',
        Profile::BASIC, // validates against a specific profile
        // add more attachments if needed
        [
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
```

### Parse Factur-X PDF
Parse a Factur-X PDF file, the parser will look by default for files `factur-x.xml` and `zugferd-invoice.xml`.

Please note that the filename `zugferd-invoice.xml` is not used since the version 2.3 of ZUGFeRD.
You can specify the files you want to search for
```php
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Parser;

// PDF path
$pdf = '/path/to/invoice.pdf';

try {
    // this will look for 'factur-x.xml' or 'zugferd-invoice.xml' files inside the given PDF
    $xml = Parser::getXml(
        $pdf,
        XmlFilename::FACTUR_X, // look only for 'factur-x.xml'
    );
} catch (UnableToExtractXmlException $e) {
    // This probably means that the file is not Factur-X
    $message = $e->getMessage();
}
```

---

## Bug reports
Create an issue using the bug report template.

---

## Contributions
Contributions are welcome, here are some guidelines:
- Code must be PSR-12
- Provide an explanation of the changes
- Provide the reason for the changes
- If needed, update the documentation and provide an example.

---

## Roadmap
- Add support for generating `en16931` and `extended` profiles
- Factur-X parsing: parse a Factur-X file to an `Invoice` object.
