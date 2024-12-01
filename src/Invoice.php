<?php

namespace MahdiAbderraouf\FacturX;

use MahdiAbderraouf\FacturX\Builders\BuilderFactory;
use MahdiAbderraouf\FacturX\Enums\FacturXProfile;

class Invoice
{
    private FacturXProfile $profile;

    public function __construct(FacturXProfile $profile)
    {
        $this->profile = $profile;
    }

    public function toXml(): string
    {
        return BuilderFactory::factory($this->profile)
            ->build();
    }
}
