<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\PaymentMeans;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Payment
{
    public string $paymentMeansTypeCode;

    public function __construct(
        PaymentMeans|string $paymentMeansTypeCode,
        public ?string $debitedAccountIdentifier = null,
        public ?string $paymentAccountIdentifier = null,
        public ?string $nationalAccountNumber = null,
    ) {
        $this->paymentMeansTypeCode = Utils::stringOrEnumToString($paymentMeansTypeCode);
    }
}
