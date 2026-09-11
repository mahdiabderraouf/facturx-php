<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class ExchangedDocumentTest extends TestCase
{
    private const DOCUMENT = '//rsm:ExchangedDocument';
    private const NOTE = self::DOCUMENT . '/ram:IncludedNote';

    public function test_renders_number_type_code_and_issue_date_in_format_102(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathValue(self::DOCUMENT . '/ram:ID', 'F-2024-12-15-0002', $xml);
        $this->assertXPathValue(self::DOCUMENT . '/ram:TypeCode', '380', $xml);
        $this->assertXPathValue(self::DOCUMENT . '/ram:IssueDateTime/udt:DateTimeString', '20241215', $xml);
        $this->assertXPathValue(self::DOCUMENT . '/ram:IssueDateTime/udt:DateTimeString/@format', '102', $xml);
    }

    public function test_includes_one_note_per_entry_with_its_subject_code_at_basic_wl(): void
    {
        $xml = self::buildXml(self::invoiceData('basicwl'));

        $this->assertXPathCount(self::NOTE, 2, $xml);
        $this->assertXPathValue(self::NOTE . '[1]/ram:Content', 'Payment due within 30 days.', $xml);
        $this->assertXPathValue(self::NOTE . '[1]/ram:SubjectCode', 'AAI', $xml);
        $this->assertXPathValue(self::NOTE . '[2]/ram:SubjectCode', 'PMD', $xml);
    }

    public function test_omits_notes_below_basic_wl(): void
    {
        $data = self::invoiceData('basicwl');
        $data['profile'] = Profile::MINIMUM;

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::NOTE, $xml);
    }

    public function test_omits_notes_when_unset(): void
    {
        $data = self::invoiceData('basicwl');
        unset($data['notes']);

        $xml = self::buildXml($data);

        $this->assertXPathMissing(self::NOTE, $xml);
    }
}
