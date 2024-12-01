<?php

namespace MahdiAbderraouf\FacturX\Builders;

use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Enums\FacturXProfile;
use MahdiAbderraouf\FacturX\Interfaces\BuilderInterface;

class BuilderFactory
{
    public static function factory(FacturXProfile $facturXProfile): BuilderInterface
    {
        return match ($facturXProfile) {
            FacturXProfile::MINIMUM => new MinimumBuilder(),
            default => throw new InvalidArgumentException('Invalid argument $facturXProfile'),
        };
    }
}
