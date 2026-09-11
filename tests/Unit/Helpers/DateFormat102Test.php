<?php

namespace MahdiAbderraouf\FacturX\Tests\Unit\Helpers;

use DateTime;
use DateTimeImmutable;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Tests\TestCase;

class DateFormat102Test extends TestCase
{
    public function test_formats_a_date_as_yyyymmdd_dropping_the_time(): void
    {
        $this->assertSame('20241202', DateFormat102::toFormat102(new DateTime('2024-12-02 15:30:45')));
    }

    public function test_accepts_an_immutable_date(): void
    {
        $this->assertSame('20240101', DateFormat102::toFormat102(new DateTimeImmutable('2024-01-01')));
    }

    public function test_parses_yyyymmdd_to_midnight(): void
    {
        $date = DateFormat102::fromFormat102('20241202');

        $this->assertSame('2024-12-02 00:00:00', $date->format('Y-m-d H:i:s'));
    }
}
