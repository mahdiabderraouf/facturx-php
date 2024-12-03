<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use InvalidArgumentException;
use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\DeliveryLocationSchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\InvoiceTypeCode;
use MahdiAbderraouf\FacturX\Enums\NoteSubjectCode;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Helpers\Utils;

class Invoice
{
    public string $typeCode;

    /**
     * @param ?array<VatBreakdown>
     * @param ?array<Allowance>
     * @param ?array<Charge>
     */
    public function __construct(
        public Profile $profile,
        public string $number,
        InvoiceTypeCode|string $typeCode,
        public DateTime $issueDate,
        public float $totalAmountWithoutVAT,
        public float $totalVATAmount,
        public float $totalAmountWithVAT,
        public float $amountDueForPayment,
        public Buyer $buyer,
        public Seller $seller,
        public string $businessProcessType = 'A1',
        public string $currencyCode = 'EUR',
        public string $vatCurrency = 'EUR',
        public ?float $lineNetAmount = null,
        public ?float $chargesSum = null,
        public ?float $allowancesSum = null,
        public ?float $paidAmount = null,
        public ?string $purchaseOrderReference = null,
        public ?string $note = null,
        public ?NoteSubjectCode $noteSubjectCode = NoteSubjectCode::GENERAL_INFORMATION,
        public ?string $deliverToLocationIdentifier = null,
        public ?string $deliverToLocationGlobalIdentifier = null,
        public ?DeliveryLocationSchemeIdentifier $deliverToLocationGlobalIdentifierSchemeIdentifier = null,
        public ?string $deliverToPartyName = null,
        public ?Address $deliverToAddress = null,
        public ?DateTime $actualDeliveryDate = null,
        public ?string $issuerAssignedID = null,
        public ?string $bankAssignedCreditorIdentifier = '',
        public ?string $remittanceInformation = '',
        public ?string $vatAccountingCurrencyCode = '',
        public ?Payee $payee = null,
        public ?Payment $payment = null,
        public ?array $vatBreakdowns = null,
        public ?DateTime $invoicingPeriodStartDate = null,
        public ?DateTime $invoicingPeriodEndDate = null,
        public ?array $allowances = null,
        public ?array $charges = null,
        public ?Payterm $payterm = null,
        public ?array $precedingInvoices = null,
        public ?DateTime $precedingInvoiceDate = null,
    ) {
        if (strlen($currencyCode) !== 3) {
            throw new InvalidArgumentException('$currencyCode must be contain 3 characters');
        }
        $this->currencyCode = strtoupper($currencyCode);

        if (strlen($vatCurrency) !== 3) {
            throw new InvalidArgumentException('$vatCurrency must be contain 3 characters');
        }
        $this->vatCurrency = strtoupper($vatCurrency);

        $this->typeCode = Utils::stringOrEnumToString($typeCode);

        if ($vatAccountingCurrencyCode && strlen($vatAccountingCurrencyCode) !== 3) {
            throw new InvalidArgumentException('$vatAccountingCurrencyCode must be contain 3 characters');
        }
        $this->vatAccountingCurrencyCode = strtoupper($vatAccountingCurrencyCode);
    }

    public function toXml(): string
    {
        return Builder::build($this);
    }
}
