<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\VatCategory;

class Charge extends Allowance
{
    public bool $isAllowance = false;
}
