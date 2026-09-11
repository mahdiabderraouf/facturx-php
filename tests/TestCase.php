<?php

namespace MahdiAbderraouf\FacturX\Tests;

use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Models\Invoice;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    private array $tempPaths = [];

    protected function tearDown(): void
    {
        foreach ($this->tempPaths as $path) {
            @unlink($path);
        }

        libxml_clear_errors();
        libxml_use_internal_errors(false);
    }

    protected static function fixture(string $relativePath): string
    {
        return __DIR__ . '/Fixtures/' . $relativePath;
    }

    protected static function invoiceData(string $profile): array
    {
        return require self::fixture('invoices/' . $profile . '.php');
    }

    protected static function buildXml(array $invoiceData): string
    {
        return Builder::build(Invoice::createFromArray($invoiceData));
    }

    protected function tempPath(string $suffix = ''): string
    {
        $path = tempnam(sys_get_temp_dir(), 'facturx-test-') . $suffix;
        $this->tempPaths[] = $path;

        return $path;
    }

    protected static function catchThrowable(callable $action): Throwable
    {
        try {
            $action();
        } catch (Throwable $throwable) {
            return $throwable;
        }

        self::fail('Expected an exception, none was thrown');
    }

    protected static function assertXPathValue(string $xpath, string $expected, string $xml): void
    {
        $nodes = Utils::getDomXPath($xml)->query($xpath);

        self::assertSame(1, $nodes->length, 'Expected exactly one node for ' . $xpath);
        self::assertSame($expected, $nodes->item(0)->nodeValue, 'Unexpected value at ' . $xpath);
    }

    protected static function assertXPathCount(string $xpath, int $expected, string $xml): void
    {
        $count = Utils::getDomXPath($xml)->query($xpath)->length;

        self::assertSame($expected, $count, 'Unexpected node count for ' . $xpath);
    }

    protected static function assertXPathMissing(string $xpath, string $xml): void
    {
        self::assertXPathCount($xpath, 0, $xml);
    }
}
