<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit;

use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;
use PHPUnit\Framework\Attributes\DataProvider;

class BuilderTest extends TestCase
{
    public static function generatedProfiles(): array
    {
        return [
            'minimum' => ['minimum', Profile::MINIMUM],
            'basicwl' => ['basicwl', Profile::BASIC_WL],
            'basic' => ['basic', Profile::BASIC],
        ];
    }

    #[DataProvider('generatedProfiles')]
    public function test_output_validates_against_the_profile_xsd(string $fixture, Profile $profile): void
    {
        $xml = Builder::build(Invoice::createFromArray(self::invoiceData($fixture)));

        $this->assertTrue(Validator::validate($xml, $profile));
    }

    #[DataProvider('generatedProfiles')]
    public function test_output_declares_the_invoice_profile_urn(string $fixture, Profile $profile): void
    {
        $xml = Builder::build(Invoice::createFromArray(self::invoiceData($fixture)));

        $this->assertXPathValue(
            '//rsm:ExchangedDocumentContext/ram:GuidelineSpecifiedDocumentContextParameter/ram:ID',
            $profile->value,
            $xml
        );
    }

    #[DataProvider('generatedProfiles')]
    public function test_output_matches_the_checked_in_snapshot(string $fixture): void
    {
        $xml = Builder::build(Invoice::createFromArray(self::invoiceData($fixture)));

        $this->assertSame(file_get_contents(self::fixture('xml/' . $fixture . '.xml')), $xml);
    }
}
