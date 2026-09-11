# Test Plan

Goal: a PHPUnit + ParaTest suite that catches regressions in XML generation,
XSD compliance, PDF embedding, and extraction, running on PHP 8.2 to 8.5 in
CI. Rules for individual tests live in
`.claude/skills/testing-best-practices/SKILL.md`; this document says what gets
built and in which order.

## 1. Tooling

| Item | Choice | Reason |
| --- | --- | --- |
| Runner | `phpunit/phpunit ^11.5` | Last major supporting PHP 8.2. PHPUnit 12 needs 8.3. |
| Parallel | `brianium/paratest ^7.7` | Wraps PHPUnit 11, one process per test class. |
| Coverage | Xdebug, already in the Docker image | Off by default, on with `XDEBUG_MODE=coverage`. |
| Autoload | `autoload-dev` PSR-4 `MahdiAbderraouf\FacturX\Tests\` to `tests/` | |
| Scripts | `composer test` runs paratest, `composer test:unit` runs the Unit suite only | Unit suite needs no poppler. |

`phpunit.xml`:

- Two suites: `Unit` (`tests/Unit`) and `Integration` (`tests/Integration`).
- `failOnRisky`, `failOnWarning`, `beStrictAboutOutputDuringTests` on.
- `executionOrder="random"` so order dependence fails locally, not only under
  ParaTest.
- Source filter on `src/` for coverage.

`tests/TestCase.php`, extended by every test:

- `assertXPathValue(string $xpath, string $expected, string $xml)` and
  `assertXPathMissing(string $xpath, string $xml)` built on
  `Utils::getDomXPath()`.
- `assertXPathCount(string $xpath, int $expected, string $xml)` for repeated
  nodes such as lines, notes, and VAT breakdowns.
- `buildXml(array $invoiceData): string` for `Builder::build()` of a fixture
  array, and `invoiceData(string $profile): array` to load one.
- `catchThrowable(callable $action): Throwable` so a test can inspect an
  exception without a `try/catch` in its body.
- `tempPath(string $suffix = ''): string` returning a unique path registered
  for deletion in `tearDown()`.
- `tearDown()` also calls `libxml_clear_errors()` and
  `libxml_use_internal_errors(false)`.

CI (`.github/workflows/ci.yml`), shipped with Wave 1 so the step never runs
against an empty suite:

- Install `poppler-utils` via `apt-get` before `composer install`.
- Keep the PHP matrix and `composer validate --strict`.
- Replace the syntax-check step with a `Run tests` step:
  `vendor/bin/paratest --no-coverage`.
- Add `vendor/bin/phpcs` and `vendor/bin/rector process --dry-run`, since the
  suite makes the run long enough that a separate lint step is free.

`CLAUDE.md`, same PR:

- Replace "No unit tests exist. CI runs syntax checking only." with the test
  commands: `vendor/bin/paratest` for the suite,
  `vendor/bin/phpunit --filter test_name` while iterating, `--testsuite Unit`
  without poppler.
- Add `composer test` to the "Before Submitting Changes" list as step 5, and
  move "Verify XML output against XSD" into it, because the Builder tests do
  that.
- Add one line under "Coding Conventions": read
  `.claude/skills/testing-best-practices/SKILL.md` before touching `tests/`,
  and every behavior change ships with a test.

## 2. Fixtures (`tests/Fixtures/`)

| File | Source | Used by |
| --- | --- | --- |
| `invoices/minimum.php`, `basicwl.php`, `basic.php` | Arrays returned from examples 4, 5, 6 with fixed dates | `Invoice::createFromArray()` in every builder test |
| `xml/minimum.xml`, `basicwl.xml`, `basic.xml` | `Builder::build()` output of the arrays above, checked in once | Validator, Parser, Generator, Builder snapshot |
| `xml/en16931.xml`, `xml/extended.xml` | XML extracted from the official Factur-X 1.08 example PDFs (`F20260023`), byte order mark kept | Validator, `getProfile()` |
| `xml/default-namespace.xml` | Hand-written MINIMUM header using `xmlns=` instead of prefixes | `getProfile()`, `extractBaseData()` regression |
| `xml/malformed.xml` | Truncated `basic.xml` | Error paths |
| `xml/unknown-profile.xml` | `basic.xml` with the URN replaced | `getProfile()` |
| `pdf/blank.pdf` | One-page PDF produced by FPDF, checked in | Generator input |
| `pdf/facturx-basic.pdf` | `Generator::generate()` of `blank.pdf` + `basic.xml`, checked in | Parser, Validator with PDF source |
| `pdf/official/en16931.pdf`, `pdf/official/extended.pdf` | Official Factur-X 1.08 example PDFs, the two profiles the library cannot generate | Wave 4 `Parser::getXml()` on third-party PDFs |
| `not-a-pdf.txt` | Plain text | `NotPdfFileException` paths |

Fixture invoice arrays are the single source of truth for a valid invoice at
each profile. A builder test copies one and changes one field. The 1.09.2
package contains no sample invoices, only specifications and schemas, so the
1.08 package supplies the official examples.

## 3. Test Inventory

Ordered by value per line. Each wave is mergeable on its own.

### Wave 1: contracts and the XSD (done)

| Class | Test cases |
| --- | --- |
| `Enums/Profile` | `isAtLeast()` full 6x6 matrix via data provider. `toBaseProfile()` maps `EXTENDED_CTC_FR` to `EXTENDED` and is identity elsewhere. `toConformanceLevel()` returns `BASIC WL` for `BASIC_WL` and the case name elsewhere. |
| `Enums/AttachmentRelationship` | `isAllowedForFacturxXml()` false for `SUPPLEMENT` and `UNSPECIFIED`, true for the rest. |
| `Builder` | Output validates against XSD for MINIMUM, BASIC_WL, BASIC using the fixture arrays. Output declares the profile URN. Output matches the checked-in snapshot. |
| `Validator` | `validate()` returns true for each fixture XML string and path. Throws `InvalidXmlException` with non-empty `getErrors()` for a BASIC XML validated as MINIMUM. Throws for malformed XML, empty string, unknown URN, missing path. `EXTENDED_CTC_FR` validates against the extended XSD. `isValid()` returns false instead of throwing. libxml error handling is restored after a failure. |
| `Parser::getProfile()` | Returns the enum for each of the five fixture files and the default-namespace file. Distinguishes `EXTENDED_CTC_FR`. Throws for missing profile node, unknown URN, malformed XML. Accepts XML without a declaration. |
| `Parser::extractBaseData()` | Returns issue date at midnight, supplier name, document number from `basic.xml` and from the default-namespace file. |
| `Models/Address` | Rejects a country code that is not two characters. Uppercases the code. Optional fields default to empty strings. |
| `Models/Invoice` | Rejects a currency, VAT currency, or accounting currency that is not three characters. Uppercases all three. Accepts `typeCode` as enum or string and stores the string. |

### Wave 2: builders and profile gates (done)

One test class per gated builder. Each gate gets a present-at and an
absent-below test; each optional field gets a present and an omitted test.

| Builder | Cases |
| --- | --- |
| `SellerTradeParty` | Identifiers, global identifiers, and email present at BASIC_WL, absent at MINIMUM. Name, VAT registration, country always present. VAT registration omitted when empty. |
| `BuyerTradeParty` | Identifiers, address, email, VAT registration at BASIC_WL; only name and legal organization at MINIMUM. Name omitted when empty. |
| `PostalTradeAddress` | Country always present. Post code, lines one to three, city present at BASIC_WL only. Province present only at BASIC_WL and when set. Empty parts omitted. |
| `SpecifiedLegalOrganization` | Trading name present only at BASIC_WL and when set. Renders with trading name alone. Element omitted when no registration id and no trading name. Scheme defaults to `0009`, accepts an enum. |
| `ApplicableHeaderTradeAgreement` | Buyer reference and order reference are ungated and appear at MINIMUM. Contract reference and seller tax representative present at BASIC_WL only. Each omitted when unset. |
| `ApplicableHeaderTradeSettlement` | Only currency and monetary summation below BASIC_WL. Every settlement section present at BASIC_WL. Tax currency omitted when equal to the invoice currency or without an accounting-currency VAT amount. |
| `SpecifiedTradeSettlementHeaderMonetarySummation` | Four mandatory totals at MINIMUM. Line total, prepaid amount, second tax total at BASIC_WL. Charge and allowance totals when set, omitted when null. Paid amount omitted when null, rendered when zero. Amounts are PHP float strings (`500`, `22.25`), not fixed two decimals. |
| `ApplicableHeaderTradeDelivery` | Empty element below BASIC_WL and without delivery data. Ship-to party, delivery date in format 102, despatch advice at BASIC_WL. Every optional part omitted when unset. |
| `ExchangedDocument` | Number, type code, issue date in format 102. One note per entry with subject code at BASIC_WL, absent at MINIMUM, absent when unset. |
| `IncludedSupplyChainTradeLineItem` | One item per line at BASIC, none at BASIC_WL. Minimal line without optional parts. Full line with note, global id, gross price, basis quantity, VAT rate. Price discount as allowance on the gross price. Unit code omitted when the unit is empty. Line billing period and line allowances. |
| `ApplicableTradeTax` | Amounts, category, rate. One element per breakdown. Exemption reason, reason code, tax point date code omitted by default and rendered when set. Omitted without breakdowns. |
| `SpecifiedTradeSettlementPaymentMeans` | Type code and both accounts. Omitted without payment. Both accounts omitted with type code only. Only the given payee identifier rendered. |
| `SpecifiedTradePaymentTerms` | Description and due date in format 102. Direct debit mandate when set. Description and due date omitted when unset. Omitted without payment terms. |
| `BillingSpecifiedPeriod` | Both dates, start only, end only, omitted without dates. |
| `InvoiceReferencedDocument` | Reference and issue date in format 102. One element per preceding invoice, date omitted when missing. Omitted without preceding invoices. |
| `URIUniversalCommunication` (electronic address BT-34/BT-49, formerly `Email`) | Escapes `"`, `<`, `&` in the scheme identifier and the raw value is absent. Accepts a string scheme. Omitted when the address is empty. The address itself is not escaped (known gap, see CLAUDE.md). |
| `SpecifiedTradeAllowanceCharge` | Allowances marked false, charges true. Percent, reason, category tax with optional rate. Basis amount when set. Bare amount omits every optional part. One element per allowance and XSD valid with two. Omitted without allowances or charges. |
| `PayeeTradeParty` | Identifiers, global identifier with scheme, name, legal organization. Only the name when identifiers are unset. Omitted without payee. |
| Leaf builders (`ContractReferencedDocument`, `BuyerOrderReferencedDocument`, `CreditorReferenceID`, `PaymentReference`, `TaxCurrencyCode`, `ReceivableSpecifiedTradeAccountingAccount`, `DespatchAdviceReferencedDocument`, `SpecifiedTaxRegistration`) | Rendered values are asserted in the parent builder tests. Omission when empty is one data provider in `OptionalHeaderElementsTest`. |

### Wave 3: helpers and models (done)

| Class | Cases |
| --- | --- |
| `Helpers/DateFormat102` | `toFormat102()` of a known date is `20241202`. `fromFormat102('20241202')` is that date at 00:00:00. |
| `Helpers/Utils` | `isPdfFile()` true for fixture PDF, false for text, XML, and missing path. `isXmlFile()` true for fixture XML with and without BOM and for plain text starting with a tag, false for text, PDF, missing path. `loadXml()` accepts a path and a string, throws for empty, whitespace, malformed, plain text. `getDomXPath()` resolves the four prefixes on a default-namespace document. `stringOrEnumToString()` for enum, string, null. `isValidXmlFilenames()` accepts any subset of known names and the full list, rejects unknown names. `isValidProfile()`. |
| `Models/Line` | Invoiced and price quantity units stored as strings, price unit nullable. Scheme identifier defaults to `0160` and accepts enum or string. |
| `Models/Payment` | Payment means type code stored as string. Account identifiers default to null. |
| `Models/Payee` | Scheme identifiers stored as strings, null becomes empty string. `createFromArray()` defaults every identifier to empty string. |
| `Models/Buyer`, `Models/Seller` | Electronic address and scheme, deprecated `email` aliases (see `ElectronicAddressTest`). |
| `Models/Invoice::createFromArray()` | Nested `lines`, `notes`, `vatBreakdowns`, `allowances`, `charges`, `delivery`, `payee`, `payment`, `payterm` mapped to models. Absent optional sections yield null. `bankAssignedCreditorIdentifier`, `remittanceInformation`, `vatAccountingCurrencyCode` default to empty string, not null. |

### Wave 4: integration (`tests/Integration/`, `#[Group('poppler')]`)

| Class | Cases |
| --- | --- |
| `Parser::getXml()` | Returns the embedded XML from `facturx-basic.pdf`, byte-equal to `basic.xml`. Returns the XML of each official PDF and it validates. Throws `NotPdfFileException` for text input. Throws `UnableToExtractXmlException` for `blank.pdf`. Single `XmlFilename::FACTUR_X` argument works. A PDF with ten or more attachments where the XML is last still extracts (covers the `XmlExtractor` index parse fix). |
| `Generator::generate()` | Round trip: `blank.pdf` + `basic.xml` to an output path, then `Parser::getXml()` returns the input XML and `Validator::validate()` passes on the PDF. Returns a string starting with `%PDF` when no output path. Accepts an `Invoice` model. Throws `NotPdfFileException` for text input. Throws `InvalidArgumentException` for `SUPPLEMENT` relationship, for non-`DATA` relationship on MINIMUM and BASIC_WL, and for a missing additional attachment. Throws `InvalidXmlException` for a BASIC XML declared as MINIMUM. XMP contains the conformance level and document number. Additional attachment is listed by `pdfdetach -list`. |
| `Validator` with PDF source | `validate('facturx-basic.pdf')` returns true. |
| `XmlExtractor` | Covered through `Parser::getXml()`. No direct test. |

## 4. Defects Found

Rule agreed with the maintainer: a real defect is fixed in the wave that finds
it and covered by a test; normal behavior is asserted as is.

### Fixed in Wave 1

- `Validator::validate()` never enabled `libxml_use_internal_errors(true)`
  before `schemaValidate()`, so schema errors leaked as PHP warnings and
  `InvalidXmlException::getErrors()` was always empty. One line added.
- `Utils::isXmlFile()` rejected XML files starting with a UTF-8 byte order
  mark, which every official Factur-X example carries.
  `Validator::validate('/path.xml')` then fell through to `Parser::getXml()`
  and threw `NotPdfFileException`. `ltrim()` now strips the BOM bytes.

### Fixed in Wave 2

- `SpecifiedTradeAllowanceCharge` wrapped every allowance in one
  `<ram:SpecifiedTradeAllowanceCharge>` element, so two document-level
  allowances or charges failed XSD validation. The wrapper now repeats per
  entry.
- `ShipToTradeParty` and `PayeeTradeParty` always emitted `<ram:ID>` and
  `<ram:GlobalID schemeID="">` even when the identifier was unset.
  `Identifiers::build()` and `GlobalIdentifiers::build()` now skip empty
  identifiers, which fixes every caller.
- `Allowance::createFromArray()` read `vatCategory` without a null guard while
  the constructor accepts null, so an allowance array without VAT category
  raised an undefined-key warning.

### Fixed in Wave 3

- `Utils::isValidXmlFilenames()` had the `array_diff()` arguments swapped, so
  only the full enum list passed and `Parser::getXml($pdf, XmlFilename::FACTUR_X)`
  threw `InvalidArgumentException`. Arguments swapped back.
- `XmlExtractor::getXmlAttachmentIndex()` read only the first character of the
  `pdfdetach -list` line, so attachment index 10 or higher was parsed as 1.
  Now casts the whole line, which PHP reads as the leading integer.
  `strpos()` used as a boolean replaced by `str_contains()`.
- `Line` declared `schemeIdentifier = '0160'` on the property but the
  constructor overwrote it with null, so a standard identifier without an
  explicit scheme rendered `schemeID=""`. The constructor now falls back to
  `0160`.
- `Payee` accepted null scheme identifiers in its signature but assigned them
  to non-nullable string properties, a `TypeError`. Null now becomes `''`.

### Observed, current behavior asserted

- `Builder::build()` emits `<?xml version="1.0"?>` without
  `encoding="UTF-8"`, because `loadXML()` replaces the document's declared
  encoding. Valid XML (UTF-8 is the default) and pinned by the snapshot test.
  Add the attribute only if a consumer needs it.
- `URIUniversalCommunication` escapes only the scheme identifier, not the
  address. Listed as a known gap in CLAUDE.md.
- The contact email `EmailURIUniversalCommunication` (BT-43/BT-58) lives in
  `DefinedTradeContact`, which only exists from EN16931. Not modelled yet;
  belongs to the EN16931 roadmap item as a `Contact` model.

- `Invoice::createFromArray()` defaults `bankAssignedCreditorIdentifier`,
  `remittanceInformation`, and `vatAccountingCurrencyCode` to `''` while the
  constructor defaults them to `null`. Builders treat both as falsy, so output
  is unaffected. Asserted as is.

## 5. Definition of Done

- `docker compose -f docker/docker-compose.yml exec facturx vendor/bin/paratest`
  passes.
- `vendor/bin/phpunit --order-by=random` passes three runs in a row.
- `vendor/bin/phpunit --testsuite Unit` passes on a host without poppler.
- Every builder with a profile gate has a test class.
- All three generated fixture XMLs validate against their XSD.
- CI matrix green on 8.2, 8.3, 8.4, 8.5.
- `phpcs` and `rector --dry-run` clean, tests included.
- README "Contributing" section mentions `composer test`.
