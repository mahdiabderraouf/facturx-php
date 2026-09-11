<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;
use PHPUnit\Framework\Attributes\DataProvider;

class ValidatorTest extends TestCase
{
    public static function validFixtures(): array
    {
        return [
            'minimum' => ['minimum'],
            'basicwl' => ['basicwl'],
            'basic' => ['basic'],
            'en16931 official sample with a byte order mark' => ['en16931'],
            'extended official sample with a byte order mark' => ['extended'],
        ];
    }

    #[DataProvider('validFixtures')]
    public function test_validates_an_xml_string_against_its_detected_profile(string $fixture): void
    {
        $xml = file_get_contents(self::fixture('xml/' . $fixture . '.xml'));

        $this->assertTrue(Validator::validate($xml));
    }

    #[DataProvider('validFixtures')]
    public function test_validates_an_xml_file_path_against_its_detected_profile(string $fixture): void
    {
        $this->assertTrue(Validator::validate(self::fixture('xml/' . $fixture . '.xml')));
    }

    public function test_extended_ctc_fr_validates_against_the_extended_xsd(): void
    {
        $xml = str_replace(
            Profile::EXTENDED->value,
            Profile::EXTENDED_CTC_FR->value,
            file_get_contents(self::fixture('xml/extended.xml'))
        );

        $this->assertTrue(Validator::validate($xml));
    }

    public function test_throws_with_libxml_errors_when_the_explicit_profile_is_stricter_than_the_document(): void
    {
        $xml = file_get_contents(self::fixture('xml/basic.xml'));

        $exception = self::catchThrowable(fn () => Validator::validate($xml, Profile::MINIMUM));

        $this->assertInstanceOf(InvalidXmlException::class, $exception);
        $this->assertNotEmpty($exception->getErrors());
    }

    public function test_throws_for_malformed_xml(): void
    {
        $this->expectException(InvalidXmlException::class);

        Validator::validate(self::fixture('xml/malformed.xml'));
    }

    public function test_throws_for_an_empty_string(): void
    {
        $this->expectException(InvalidXmlException::class);

        Validator::validate('');
    }

    public function test_throws_for_an_unknown_profile_urn(): void
    {
        $this->expectException(InvalidXmlException::class);

        Validator::validate(self::fixture('xml/unknown-profile.xml'));
    }

    public function test_treats_a_missing_path_as_an_xml_string(): void
    {
        $this->expectException(InvalidXmlException::class);

        Validator::validate('/nonexistent/factur-x.xml');
    }

    public function test_is_valid_returns_false_instead_of_throwing(): void
    {
        $this->assertFalse(Validator::isValid(self::fixture('xml/malformed.xml')));
    }

    public function test_is_valid_returns_true_for_a_valid_document(): void
    {
        $this->assertTrue(Validator::isValid(self::fixture('xml/basic.xml'), Profile::BASIC));
    }

    public function test_restores_libxml_error_handling_after_a_failure(): void
    {
        self::catchThrowable(fn () => Validator::validate(self::fixture('xml/basic.xml'), Profile::MINIMUM));

        $this->assertFalse(libxml_use_internal_errors());
    }
}
