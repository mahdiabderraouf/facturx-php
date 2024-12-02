<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\NoteSubjectCode;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class Invoice
{
    public int $typeCode;

    public function __construct(
        public Profile $profile,
        public string $number,
        public string $documentName,
        InvoiceTypeCode|int $typeCode,
        public DateTime $issueDate,
        public string $businessProcessType = 'A1',
        public string $currencyCode = 'EUR',
        public string $vatCurrency = 'EUR',
        public float $totalAmountWithoutVAT,
        public float $totalVATAmount,
        public float $totalAmountWithVAT,
        public float $amountDueForPayment,
        public Buyer $buyer,
        public Seller $seller,
        public ?string $purchaseOrderReference = null,
        public ?string $note = null,
        public ?NoteSubjectCode $noteSubjectCode = NoteSubjectCode::GENERAL_INFORMATION,
        public ?string $bankAssignedCreditorIdentifier,
        public ?string $remittanceInformation = '',
        public ?string $vatAccountingCurrencyCode = '',
        public ?string $payeeIdentifier = '',
        public ?string $payeeGlobalIdentifier = '',
        public SchemeIdentifier|string|null $payeeGlobalSchemeIdentifier = '',
        public ?string $payeeName = '',
        public ?string $payeeLegalRegistrationIdentifier = '',
        public SchemeIdentifier|string|null $payeeLegalRegistrationSchemeIdentifier = '',
    ) {
        if (strlen($currencyCode) !== 3) {
            throw new InvalidArgumentException('$currencyCode must be contain 3 characters');
        }
        $this->currencyCode = strtoupper($currencyCode);

        if (strlen($vatCurrency) !== 3) {
            throw new InvalidArgumentException('$vatCurrency must be contain 3 characters');
        }
        $this->vatCurrency = strtoupper($vatCurrency);

        $this->typeCode = $typeCode instanceof InvoiceTypeCode ? $typeCode->value : $typeCode;

        if ($vatAccountingCurrencyCode && strlen($vatAccountingCurrencyCode) !== 3) {
            throw new InvalidArgumentException('$vatAccountingCurrencyCode must be contain 3 characters');
        }
        $this->vatAccountingCurrencyCode = strtoupper($vatAccountingCurrencyCode);
        $this->payeeGlobalSchemeIdentifier = $payeeGlobalSchemeIdentifier instanceof SchemeIdentifier ? $payeeGlobalSchemeIdentifier->value : $payeeGlobalSchemeIdentifier;
        $this->payeeLegalRegistrationSchemeIdentifier = $payeeLegalRegistrationSchemeIdentifier instanceof SchemeIdentifier ? $payeeLegalRegistrationSchemeIdentifier->value : $payeeLegalRegistrationSchemeIdentifier;
    }

    public function toXml(): string
    {
        return Builder::build($this);
    }

    public function getFormattedIssueDate(): string
    {
        return DateFormat102::toFormat102($this->issueDate);
    }
}
