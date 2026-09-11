<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Parser;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ParserTest extends TestCase
{
    public static function fixtureProfiles(): array
    {
        return [
            'minimum' => ['minimum', Profile::MINIMUM],
            'basicwl' => ['basicwl', Profile::BASIC_WL],
            'basic' => ['basic', Profile::BASIC],
            'en16931' => ['en16931', Profile::EN16931],
            'extended' => ['extended', Profile::EXTENDED],
            'default namespaces' => ['default-namespace', Profile::MINIMUM],
        ];
    }

    #[DataProvider('fixtureProfiles')]
    public function test_get_profile_reads_the_guideline_urn(string $fixture, Profile $expected): void
    {
        $xml = file_get_contents(self::fixture('xml/' . $fixture . '.xml'));

        $this->assertSame($expected, Parser::getProfile($xml));
    }

    public function test_get_profile_distinguishes_extended_ctc_fr(): void
    {
        $xml = str_replace(
            Profile::EXTENDED->value,
            Profile::EXTENDED_CTC_FR->value,
            file_get_contents(self::fixture('xml/extended.xml'))
        );

        $this->assertSame(Profile::EXTENDED_CTC_FR, Parser::getProfile($xml));
    }

    public function test_get_profile_accepts_xml_without_a_declaration(): void
    {
        $xml = preg_replace('/^<\?xml[^>]*\?>\s*/', '', file_get_contents(self::fixture('xml/basic.xml')));

        $this->assertSame(Profile::BASIC, Parser::getProfile($xml));
    }

    public function test_get_profile_throws_for_an_unknown_urn(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('invalid profile found');

        Parser::getProfile(file_get_contents(self::fixture('xml/unknown-profile.xml')));
    }

    public function test_get_profile_throws_when_the_guideline_node_is_missing(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('invalid or missing profile tag');

        Parser::getProfile(
            '<rsm:CrossIndustryInvoice xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100"/>'
        );
    }

    public function test_get_profile_throws_for_malformed_xml(): void
    {
        $this->expectException(InvalidXmlException::class);

        Parser::getProfile(file_get_contents(self::fixture('xml/malformed.xml')));
    }

    public function test_extract_base_data_returns_number_supplier_and_issue_date_at_midnight(): void
    {
        $data = Parser::extractBaseData(file_get_contents(self::fixture('xml/basic.xml')));

        $this->assertSame('F-2024-12-15-0002', $data['documentNumber']);
        $this->assertSame('ACME Corp.', $data['supplier']);
        $this->assertSame('2024-12-15 00:00:00', $data['issueDate']->format('Y-m-d H:i:s'));
    }

    public function test_extract_base_data_reads_documents_with_default_namespaces(): void
    {
        $data = Parser::extractBaseData(file_get_contents(self::fixture('xml/default-namespace.xml')));

        $this->assertSame('F-DEFAULT-NS', $data['documentNumber']);
        $this->assertSame('Default NS Seller', $data['supplier']);
        $this->assertSame('2024-12-02', $data['issueDate']->format('Y-m-d'));
    }
}
