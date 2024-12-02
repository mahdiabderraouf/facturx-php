<?php

namespace MahdiAbderraouf\FacturX\Models;

use InvalidArgumentException;

class BankInformations
{
    public function __construct(
        public ?string $bankAssignedCreditorIdentifier,
        public ?string $remittanceInformation = '',
        public ?string $vatAccountingCurrencyCode = '',
        public ?string $invoiceCurrencyCode = ''
    ) {
        if (strlen($vatAccountingCurrencyCode) !== 3) {
            throw new InvalidArgumentException('$vatAccountingCurrencyCode must be contain 3 characters');
        }
        $this->vatAccountingCurrencyCode = strtoupper($vatAccountingCurrencyCode);

        if (strlen($invoiceCurrencyCode) !== 3) {
            throw new InvalidArgumentException('$invoiceCurrencyCode must be contain 3 characters');
        }
        $this->invoiceCurrencyCode = strtoupper($invoiceCurrencyCode);
    }
}
