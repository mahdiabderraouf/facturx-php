<?php

namespace MahdiAbderraouf\FacturX\Tests\Integration;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Parser;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;

#[Group('poppler')]
class ParserTest extends TestCase
{
    public function test_get_xml_returns_the_embedded_xml_unchanged(): void
    {
        $xml = Parser::getXml(self::fixture('pdf/facturx-basic.pdf'));

        $this->assertSame(file_get_contents(self::fixture('xml/basic.xml')), $xml);
    }

    #[TestWith(['en16931'])]
    #[TestWith(['extended'])]
    public function test_get_xml_extracts_valid_xml_from_official_pdfs(string $profile): void
    {
        $xml = Parser::getXml(self::fixture('pdf/official/' . $profile . '.pdf'));

        $this->assertTrue(Validator::validate($xml));
    }

    public function test_get_xml_accepts_a_single_filename_enum(): void
    {
        $xml = Parser::getXml(self::fixture('pdf/facturx-basic.pdf'), XmlFilename::FACTUR_X);

        $this->assertSame(file_get_contents(self::fixture('xml/basic.xml')), $xml);
    }

    public function test_get_xml_throws_when_only_another_filename_is_searched(): void
    {
        $this->expectException(UnableToExtractXmlException::class);

        Parser::getXml(self::fixture('pdf/facturx-basic.pdf'), XmlFilename::ZUGFERD);
    }

    public function test_get_xml_rejects_unknown_filenames(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Parser::getXml(self::fixture('pdf/facturx-basic.pdf'), ['invoice.xml']);
    }

    public function test_get_xml_throws_for_a_non_pdf_file(): void
    {
        $this->expectException(NotPdfFileException::class);

        Parser::getXml(self::fixture('not-a-pdf.txt'));
    }

    public function test_get_xml_throws_for_a_pdf_without_attachment(): void
    {
        $this->expectException(UnableToExtractXmlException::class);

        Parser::getXml(self::fixture('pdf/blank.pdf'));
    }

    public function test_get_xml_finds_the_xml_when_it_is_the_tenth_attachment(): void
    {
        $extra = $this->tempPath('.txt');
        file_put_contents($extra, 'attachment');
        $attachments = array_map(fn (int $i): array => ['file' => $extra, 'filename' => "a$i.txt"], range(1, 9));
        $pdf = $this->tempPath('.pdf');
        Generator::generate(
            self::fixture('pdf/blank.pdf'),
            self::fixture('xml/basic.xml'),
            outputPath: $pdf,
            additionalAttachments: $attachments
        );

        $xml = Parser::getXml($pdf);

        $this->assertSame(file_get_contents(self::fixture('xml/basic.xml')), $xml);
    }
}
