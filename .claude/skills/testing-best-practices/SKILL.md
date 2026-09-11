---
name: testing-best-practices
description: >-
  Test design and review for this library (PHPUnit + ParaTest). Use when
  deciding what to cover, whether to write a new test or edit an existing one,
  naming or structuring tests, choosing assertions or fixtures, isolating
  poppler/filesystem/time, testing the escaping boundary, or reviewing test
  value. Read it before writing or changing any file under tests/.
---

# Testing Best Practices

Rules for designing tests in this library. Framework syntax is not covered here:
fetch `https://docs.phpunit.de/en/11.5/` for PHPUnit and
`https://github.com/paratestphp/paratest` for ParaTest, and confirm every
attribute or assertion name there before using it.

## Consistency First

Read the tests in the same directory before choosing syntax or layout. A
pattern repeated across the suite is a convention and wins over this skill. An
existing test that follows a convention is not defective because it conflicts
with this file. Do not delete or rewrite it; explain the drawback and let the
user decide.

## What to Test

- Test observable behavior through the public API: `Builder`, `Generator`,
  `Validator`, `Parser`, the `Models` constructors and `createFromArray()`,
  and the `Enums` methods. A test must still pass after a refactor that keeps
  the behavior.
- Cover every decision: a branch, a validation, a calculation, a profile gate
  (`$profile->isAtLeast()`), an exception path.
- Cover each high-value failure mode: invalid input rejected with the specific
  custom exception, non-PDF input, missing attachment, malformed XML, unknown
  profile URN.
- Builders are tested through the XML they produce, not the string they
  concatenate. Load the output with `Utils::getDomXPath()` and assert nodes,
  values, and absence of nodes. Never assert against a raw XML substring.
- Every builder change is also validated against the XSD:
  `Validator::validate($xml, $profile)`. One passing XSD validation per profile
  is the highest-value test in this repo.
- Profile gates need two assertions: the node is present at the lowest profile
  that includes it and absent at the profile just below.
- Test the escaping boundary where it exists: put `<`, `&`, `"` in a model
  field that is escaped and assert the entity is present and the raw value is
  absent. CLAUDE.md marks unescaped fields as a known gap. Do not write a
  failing test for a field that is not escaped; report the gap instead.
- Bug fix: write the regression test first, watch it fail, then fix.

## What Not to Test

- PHP, `DOMDocument`, libxml, `DateTime`, or `mime_content_type()` behavior.
- FPDF/FPDI internals. Test `PdfA3b` only through `Generator::generate()`
  output: the file is a PDF, the XML attachment is extractable by `Parser`,
  the XMP carries the profile.
- Enum case lists or constant values. `Profile::values()` returning six URNs
  is a declaration, not behavior. Test `isAtLeast()` and `toBaseProfile()`
  instead, because they contain decisions.
- Constructor promotion, getters, and pass-through `createFromArray()` keys.
  Test `createFromArray()` only for a key that is transformed, defaulted,
  validated, or mapped to a nested model.
- The XSD files themselves. They are vendored spec artifacts.
- Anything already caught by a lower-layer test. When two tests detect the
  same defect, keep the lower one and trim the higher one to a single case
  that proves the wiring. Do not delete an existing test without approval.

## New Test or Edit an Existing One

| Situation | Action |
| --- | --- |
| New behavior, new branch, new exception | New test method. New file if the class has none. |
| Same behavior, one more input value | Add a case to the existing data provider. |
| Behavior intentionally changed | Edit the expectation in the existing test and say so in the summary. Never add a second test that contradicts the first. |
| Bug reported | New test named after the behavior, not the issue number. |
| Refactor with no behavior change | No test change. If a test breaks, the test was asserting implementation, so fix the test to assert behavior. |
| Test is flaky or slow | Fix the isolation cause. Do not add retries or `markTestSkipped()`. |

Deleting a test needs the user's explicit approval. Tests are part of the
library.

## Layout and Naming

- `tests/Unit/` mirrors `src/`: `src/Builders/SellerTradeParty.php` gets
  `tests/Unit/Builders/SellerTradePartyTest.php`.
- `tests/Integration/` holds tests that run `pdfdetach` or produce a PDF:
  `Generator`, `Parser::getXml()`, `Validator` with a PDF source,
  `XmlExtractor`.
- Fixtures live in `tests/Fixtures/` and are loaded by path. Move any literal
  longer than ten lines out of the test body into a fixture.
- Every test class extends `MahdiAbderraouf\FacturX\Tests\TestCase`.
- Methods use the `test_` prefix and snake_case. The name is a specification:
  result plus condition. Use a result verb: `returns`, `throws`, `omits`,
  `includes`, `rejects`, `uppercases`, `falls back`.

```php
public function test_omits_seller_identifiers_below_basic_wl(): void
public function test_throws_not_pdf_file_exception_for_xml_input(): void
public function test_extended_ctc_fr_validates_against_extended_xsd(): void
```

Do not write `test_build()`, `test_it_works()`, or `test_validation()`.

One test class per class under test. Use `#[Group('poppler')]` on tests that
shell out to `pdfdetach`, so a machine without poppler can exclude them. Do
not use groups for structure.

## Assertions

- Arrange, act, assert, separated by one blank line each. One action per test.
- A test body is a straight line: no `if`, `foreach`, `match`, `try/catch`,
  or ternary. Variation goes in a data provider, conditional cleanup in
  `tearDown()`, an expected exception in `expectException()`. A reader must
  understand the test without executing it in their head.
- `assertSame()`, never `assertEquals()`. For floats formatted into XML,
  assert the formatted string, not the float.
- Assert a known value written in the test. Never compute the expected value
  with the same helper the implementation uses (`DateFormat102::toFormat102()`
  in a test of `DateFormat102` proves nothing).
- XML: assert through XPath helpers on the base `TestCase`, such as
  `assertXPathValue('//ram:SellerTradeParty/ram:Name', 'ACME', $xml)` and
  `assertXPathMissing(...)`. A failure then names the node.
- Exceptions: `expectException(InvalidXmlException::class)` with the specific
  class from `src/Exceptions/`. Assert the message only when the message is
  the contract, for example the libxml errors carried by
  `InvalidXmlException`.
- Assert the complete result of a write. For `Generator::generate()` with an
  output path: the file exists, is a PDF, and `Parser::getXml()` returns the
  same XML that went in.
- Use `#[DataProvider]` with string keys naming each case for enum cases,
  boundary values, and input/output pairs. Use `#[TestWith]` for two or three
  values. Split into separate tests when setup or assertions differ; a branch
  in a test body is two tests.

## Isolation and Determinism

The library is static methods without dependency injection. Do not mock
builders, models, or helpers. Test the real code path.

- **Poppler.** `pdfdetach` is the only external process. Its tests belong in
  `tests/Integration/` with `#[Group('poppler')]`. Never mock `exec()`.
- **Filesystem.** Create temp files with `tempnam()` inside the test and
  remove them in `tearDown()`. Never use a fixed path. ParaTest runs tests in
  separate processes at the same time, so a shared filename is a race.
- **Time.** `Generator` stamps the current date into the XMP. Assert the
  format or the presence of the field, not the value. The issue date comes
  from the invoice and is a known value.
- **libxml state.** `Validator` toggles `libxml_use_internal_errors()`. A test
  that leaves it enabled hides errors in the next test. The base `TestCase`
  resets it in `tearDown()`.
- **Fixtures are read-only.** Copy a fixture PDF to a temp path before passing
  it to anything that writes.

Each test must pass alone, in the full suite, in random order, and under
ParaTest.

## Performance

- Builder and model tests are pure PHP and run in milliseconds. Keep them in
  `tests/Unit/` and run them constantly.
- `Generator` and `Parser::getXml()` spawn a process and write a PDF. Write
  one such test per behavior, not per field. Field coverage belongs in builder
  tests.
- Run `vendor/bin/paratest` for the suite and
  `vendor/bin/phpunit --filter test_name` while iterating. Run the narrowest
  set that covers the change, then the full suite once before finishing.
- Run with Xdebug off unless collecting coverage. The Docker image enables it;
  pass `-d xdebug.mode=off` or set `XDEBUG_MODE=off`.

## Review Checklist

Check every item before completion. Report findings; do not rewrite tests
without approval. Report a suite-wide pattern once.

- [ ] Each test asserts behavior through the public API, not string
      concatenation or a private method.
- [ ] Each changed decision and each applicable failure mode has a test.
- [ ] Each profile gate has a present-at and an absent-below assertion.
- [ ] Each builder change has an XSD validation for every affected profile.
- [ ] Each test detects a defect no other test detects.
- [ ] File path mirrors `src/`, class extends the base `TestCase`, method
      names state result and condition.
- [ ] Expected values are literals, not computed by the implementation.
- [ ] No test body contains a branch, a loop, or a `try/catch`.
- [ ] Every comparison is `assertSame()`; every XML check goes through XPath.
- [ ] Data provider keys name the case.
- [ ] Temp files are unique and removed; no fixed paths; fixtures untouched.
- [ ] Tests that spawn `pdfdetach` are in `tests/Integration/` with
      `#[Group('poppler')]`.
- [ ] The suite passes under `vendor/bin/paratest` and with
      `--order-by=random`.
