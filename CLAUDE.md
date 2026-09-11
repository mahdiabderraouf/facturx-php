# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Public PHP library for generating and validating Factur-X/ZUGFeRD compliant PDF invoices. Code quality and security are the top priorities. Namespace: `MahdiAbderraouf\FacturX`.

## Docker

All commands MUST be run inside the Docker container. Never run PHP, Composer, or any tool directly on the host.

```bash
# Start the container
docker compose -f docker/docker-compose.yml up -d

# Run a command inside the container
docker compose -f docker/docker-compose.yml exec facturx <command>
```

## Commands

All via `docker compose -f docker/docker-compose.yml exec facturx`:

```bash
composer install                  # Install dependencies
composer validate --strict        # Validate composer.json
vendor/bin/phpcs                  # Code style (PSR-12 via phpcs.xml)
vendor/bin/rector process --dry-run  # Rector checks
bash -c 'find src -name "*.php" -print0 | xargs -0 -n1 php -l'  # Syntax check
vendor/bin/paratest --no-coverage           # Full test suite (needs poppler-utils, present in Docker)
vendor/bin/phpunit --testsuite Unit         # Unit tests only, no poppler needed
vendor/bin/phpunit --filter test_name       # One test while iterating
```

CI runs composer validate, phpcs, and the full suite on PHP 8.2 to 8.5.

## Architecture

Public API (4 facade classes — everything else is internal):

- **Builder** — generates Factur-X XML from an `Invoice` model
- **Generator** — embeds XML into a PDF to produce a PDF/A-3b compliant file
- **Validator** — validates XML against profile-specific XSD schemas
- **Parser** — extracts embedded XML from a Factur-X PDF

### Key patterns

- **Builders/**: static `build()` methods returning XML string fragments via heredoc/concatenation. Compose hierarchically.
- **Models/**: data objects with constructor promotion and `createFromArray()` factory.
- **Enums/**: backed enums with `HasValues` trait. `Profile` defines five levels (MINIMUM → EXTENDED); builders gate fields with `$profile->isAtLeast(Profile::X)`.
- **Fpdi/PdfA3b**: FPDI subclass for XMP metadata, ICC color profiles, and PDF/A-3b attachment embedding.

## Coding Conventions

- PSR-12 (enforced by `phpcs.xml`), PSR-4 autoloading
- PHP >=8.2, <=8.5. Do not use 8.3+ features without bumping the minimum version.
- Static methods for builders (`build()`) and model factories (`createFromArray()`). No instance methods or DI in these classes.
- Prefer early returns and specific custom exceptions over nested conditionals or generic `\Exception`.
- Enums also accept string backing values (via `Utils::stringOrEnumToString()`).
- Comments: 0 by default. Write one only when the code cannot carry the information itself (spec quirk, non-obvious constraint, a `why`). Never restate what the code says. Keep it to one line, no block comment unless PHPDoc is required.
- Do not add Composer dependencies without strong justification. Only fpdf + fpdi.
- Tests: read `.claude/skills/testing-best-practices/SKILL.md` before touching `tests/`. Every behavior change ships with a test. Test names are snake_case specifications (`test_omits_x_below_basic_wl`), PHPUnit attributes for data providers, XPath assertions from the base `TestCase` for XML.

## Security Rules

Treat all model data as untrusted user input.

- **XML injection**: Builders interpolate values directly into XML strings. Any user-supplied data placed into XML **must** be escaped with `htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8')`. Known gap — do not widen it.
- **Shell commands**: All arguments to `exec()` **must** go through `escapeshellarg()`. No string interpolation for shell arguments.
- **Forbidden functions**: No `eval`, `shell_exec`, `system`, `passthru`, or `proc_open`. Only `exec()` in `XmlExtractor` is allowed.
- **File handling**: Validate types with `mime_content_type()`. Clean up temp files. Never expose temp paths to callers.

## Profile-Aware Development

Profiles (MINIMUM → EXTENDED) are hierarchical. When adding XML fields:

- Gate with `$profile->isAtLeast(Profile::X)`.
- `resources/xsd/` is the source of truth for which fields belong to which profile.
- Validate generated XML against XSD after changes: `Validator::validate($xml, $profile)`.

## Before Submitting Changes

Run all inside Docker (`docker compose -f docker/docker-compose.yml exec facturx`):

1. `composer validate --strict`
2. `bash -c 'find src -name "*.php" -print0 | xargs -0 -n1 php -l'`
3. `vendor/bin/phpcs`
4. `vendor/bin/rector process --dry-run`
5. `vendor/bin/paratest --no-coverage` (Builder tests validate every generated profile against its XSD)
