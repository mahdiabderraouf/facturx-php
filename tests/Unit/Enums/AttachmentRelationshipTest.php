<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Enums;

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AttachmentRelationshipTest extends TestCase
{
    #[TestWith([AttachmentRelationship::DATA, true])]
    #[TestWith([AttachmentRelationship::SOURCE, true])]
    #[TestWith([AttachmentRelationship::ALTERNATIVE, true])]
    #[TestWith([AttachmentRelationship::SUPPLEMENT, false])]
    #[TestWith([AttachmentRelationship::UNSPECIFIED, false])]
    public function test_only_data_source_and_alternative_are_allowed_for_the_xml(
        AttachmentRelationship $relationship,
        bool $expected
    ): void {
        $this->assertSame($expected, $relationship->isAllowedForFacturxXml());
    }
}
