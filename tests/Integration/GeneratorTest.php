<?php

namespace MahdiAbderraouf\FacturX\Tests\Integration;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\NotPdfFileException;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Parser;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use MahdiAbderraouf\FacturX\Validator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;

#[Group('poppler')]
class GeneratorTest extends TestCase
{
    public function test_writes_a_pdf_whose_embedded_xml_round_trips_and_validates(): void
    {
        $output = $this->tempPath('.pdf');

        Generator::generate(self::fixture('pdf/blank.pdf'), self::fixture('xml/basic.xml'), outputPath: $output);

        $this->assertTrue(Utils::isPdfFile($output));
        $this->assertSame(file_get_contents(self::fixture('xml/basic.xml')), Parser::getXml($output));
        $this->assertTrue(Validator::validate($output));
    }

    public function test_returns_the_pdf_as_a_string_without_output_path(): void
    {
        $pdf = Generator::generate(self::fixture('pdf/blank.pdf'), self::fixture('xml/basic.xml'));

        $this->assertStringStartsWith('%PDF-1.7', $pdf);
        $this->assertStringEndsWith("%%EOF\n", $pdf);
    }

    public function test_accepts_an_xml_string_and_removes_its_temporary_file(): void
    {
        $before = glob(sys_get_temp_dir() . '/FACTURX_XML*');

        $pdf = Generator::generate(self::fixture('pdf/blank.pdf'), file_get_contents(self::fixture('xml/basic.xml')));

        $this->assertStringStartsWith('%PDF', $pdf);
        $this->assertSame($before, glob(sys_get_temp_dir() . '/FACTURX_XML*'));
    }

    public function test_accepts_an_invoice_model(): void
    {
        $output = $this->tempPath('.pdf');

        Generator::generate(
            self::fixture('pdf/blank.pdf'),
            Invoice::createFromArray(self::invoiceData('minimum')),
            outputPath: $output
        );

        $this->assertSame(Profile::MINIMUM, Parser::getProfile(Parser::getXml($output)));
    }

    public function test_writes_profile_supplier_and_number_into_the_xmp_metadata(): void
    {
        $pdf = Generator::generate(self::fixture('pdf/blank.pdf'), self::fixture('xml/basicwl.xml'));

        $this->assertStringContainsString('<fx:ConformanceLevel>BASIC WL</fx:ConformanceLevel>', $pdf);
        $this->assertStringContainsString('<fx:DocumentFileName>factur-x.xml</fx:DocumentFileName>', $pdf);
        $this->assertStringContainsString('ACME Corp.: Invoice F-2024-12-15-0002', $pdf);
        $this->assertStringContainsString('<xmp:CreateDate>2024-12-15</xmp:CreateDate>', $pdf);
    }

    public function test_embeds_additional_attachments_under_their_given_names(): void
    {
        $extra = $this->tempPath('.txt');
        file_put_contents($extra, 'terms');
        $output = $this->tempPath('.pdf');

        Generator::generate(
            self::fixture('pdf/blank.pdf'),
            self::fixture('xml/basic.xml'),
            outputPath: $output,
            additionalAttachments: [['file' => $extra, 'filename' => 'terms.txt', 'description' => 'Terms']]
        );

        $this->assertSame(['factur-x.xml', 'terms.txt'], self::attachmentNames($output));
    }

    public function test_throws_for_a_non_pdf_source(): void
    {
        $this->expectException(NotPdfFileException::class);

        Generator::generate(self::fixture('not-a-pdf.txt'), self::fixture('xml/basic.xml'));
    }

    #[TestWith([AttachmentRelationship::SUPPLEMENT])]
    #[TestWith([AttachmentRelationship::UNSPECIFIED])]
    public function test_rejects_relationships_not_allowed_for_the_xml(AttachmentRelationship $relationship): void
    {
        $this->expectException(InvalidArgumentException::class);

        Generator::generate(self::fixture('pdf/blank.pdf'), self::fixture('xml/basic.xml'), $relationship);
    }

    #[TestWith(['minimum'])]
    #[TestWith(['basicwl'])]
    public function test_rejects_non_data_relationships_below_basic(string $fixture): void
    {
        $this->expectException(InvalidArgumentException::class);

        Generator::generate(
            self::fixture('pdf/blank.pdf'),
            self::fixture('xml/' . $fixture . '.xml'),
            AttachmentRelationship::ALTERNATIVE
        );
    }

    public function test_accepts_the_alternative_relationship_from_basic(): void
    {
        $pdf = Generator::generate(
            self::fixture('pdf/blank.pdf'),
            self::fixture('xml/basic.xml'),
            AttachmentRelationship::ALTERNATIVE
        );

        $this->assertStringContainsString('/AFRelationship /Alternative', $pdf);
    }

    public function test_throws_when_the_xml_does_not_match_the_requested_profile(): void
    {
        $this->expectException(InvalidXmlException::class);

        Generator::generate(self::fixture('pdf/blank.pdf'), self::fixture('xml/basic.xml'), profile: Profile::MINIMUM);
    }

    public function test_throws_for_a_missing_additional_attachment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing.pdf');

        Generator::generate(
            self::fixture('pdf/blank.pdf'),
            self::fixture('xml/basic.xml'),
            additionalAttachments: [['file' => '/nonexistent/missing.pdf']]
        );
    }

    private static function attachmentNames(string $pdfPath): array
    {
        exec('pdfdetach -list ' . escapeshellarg($pdfPath), $output);

        $names = array_map(fn (string $line): string => trim(substr($line, strpos($line, ':') + 1)), $output);

        return array_slice($names, 1);
    }
}
