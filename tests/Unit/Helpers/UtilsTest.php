<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Helpers;

use DOMDocument;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class UtilsTest extends TestCase
{
    public function test_is_pdf_file_accepts_a_pdf(): void
    {
        $this->assertTrue(Utils::isPdfFile(self::fixture('pdf/blank.pdf')));
    }

    #[TestWith(['not-a-pdf.txt'])]
    #[TestWith(['xml/basic.xml'])]
    #[TestWith(['missing.pdf'])]
    public function test_is_pdf_file_rejects_non_pdf_and_missing_files(string $fixture): void
    {
        $this->assertFalse(Utils::isPdfFile(self::fixture($fixture)));
    }

    #[TestWith(['xml/basic.xml'])]
    #[TestWith(['xml/en16931.xml'])]
    #[TestWith(['xml/default-namespace.xml'])]
    public function test_is_xml_file_accepts_xml_with_or_without_a_byte_order_mark(string $fixture): void
    {
        $this->assertTrue(Utils::isXmlFile(self::fixture($fixture)));
    }

    public function test_is_xml_file_accepts_plain_text_that_starts_with_a_tag(): void
    {
        $path = $this->tempPath('.txt');
        file_put_contents($path, "  \n<root/>\n");

        $this->assertTrue(Utils::isXmlFile($path));
    }

    #[TestWith(['not-a-pdf.txt'])]
    #[TestWith(['pdf/blank.pdf'])]
    #[TestWith(['missing.xml'])]
    public function test_is_xml_file_rejects_non_xml_and_missing_files(string $fixture): void
    {
        $this->assertFalse(Utils::isXmlFile(self::fixture($fixture)));
    }

    public function test_load_xml_accepts_a_path_and_a_string(): void
    {
        $fromPath = Utils::loadXml(self::fixture('xml/minimum.xml'));
        $fromString = Utils::loadXml('<root><child/></root>');

        $this->assertInstanceOf(DOMDocument::class, $fromPath);
        $this->assertSame('rsm:CrossIndustryInvoice', $fromPath->documentElement->tagName);
        $this->assertSame('root', $fromString->documentElement->tagName);
    }

    #[TestWith([''])]
    #[TestWith(["  \n"])]
    #[TestWith(['<root>'])]
    #[TestWith(['plain text'])]
    public function test_load_xml_throws_for_empty_or_malformed_input(string $xml): void
    {
        $this->expectException(InvalidXmlException::class);

        Utils::loadXml($xml);
    }

    public function test_get_dom_xpath_resolves_the_factur_x_prefixes(): void
    {
        $xpath = Utils::getDomXPath(self::fixture('xml/default-namespace.xml'));

        $this->assertSame(1, $xpath->query('//rsm:ExchangedDocument/ram:ID')->length);
        $this->assertSame(1, $xpath->query('//udt:DateTimeString')->length);
    }

    public function test_string_or_enum_to_string_unwraps_enums_and_passes_strings_and_null_through(): void
    {
        $this->assertSame('EM', Utils::stringOrEnumToString(\MahdiAbderraouf\FacturX\Enums\SchemeIdentifier::EMAIL));
        $this->assertSame('0225', Utils::stringOrEnumToString('0225'));
        $this->assertNull(Utils::stringOrEnumToString(null));
    }

    #[TestWith([['factur-x.xml']])]
    #[TestWith([['zugferd-invoice.xml']])]
    #[TestWith([['factur-x.xml', 'zugferd-invoice.xml']])]
    public function test_is_valid_xml_filenames_accepts_any_subset_of_known_names(array $filenames): void
    {
        $this->assertTrue(Utils::isValidXmlFilenames($filenames));
    }

    #[TestWith([['invoice.xml']])]
    #[TestWith([['factur-x.xml', 'other.xml']])]
    public function test_is_valid_xml_filenames_rejects_unknown_names(array $filenames): void
    {
        $this->assertFalse(Utils::isValidXmlFilenames($filenames));
    }

    public function test_is_valid_xml_filenames_accepts_the_full_enum_list(): void
    {
        $this->assertTrue(Utils::isValidXmlFilenames(XmlFilename::values()));
    }

    public function test_is_valid_profile_checks_the_urn(): void
    {
        $this->assertTrue(Utils::isValidProfile(Profile::BASIC->value));
        $this->assertFalse(Utils::isValidProfile('urn:example:unknown'));
    }
}
