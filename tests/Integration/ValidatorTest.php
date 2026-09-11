<?php

namespace MahdiAbderraouf\FacturX\Tests\Integration;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;

#[Group('poppler')]
class ValidatorTest extends TestCase
{
    #[TestWith(['pdf/facturx-basic.pdf'])]
    #[TestWith(['pdf/official/en16931.pdf'])]
    #[TestWith(['pdf/official/extended.pdf'])]
    public function test_validates_the_xml_embedded_in_a_pdf(string $fixture): void
    {
        $this->assertTrue(Validator::validate(self::fixture($fixture)));
    }

    public function test_validates_a_pdf_against_an_explicit_profile(): void
    {
        $this->assertTrue(Validator::validate(self::fixture('pdf/facturx-basic.pdf'), Profile::BASIC));
        $this->assertFalse(Validator::isValid(self::fixture('pdf/facturx-basic.pdf'), Profile::MINIMUM));
    }

    public function test_is_valid_returns_false_for_a_pdf_without_xml(): void
    {
        $this->assertFalse(Validator::isValid(self::fixture('pdf/blank.pdf')));
    }
}
