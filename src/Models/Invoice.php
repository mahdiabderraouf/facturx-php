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
        public ?string $contractReference = null,
        public ?string $note = null,
        public ?NoteSubjectCode $noteSubjectCode = NoteSubjectCode::GENERAL_INFORMATION,
        public ?Delivery $delivery = null,
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
        $this->vatAccountingCurrencyCode = strtoupper((string) $vatAccountingCurrencyCode);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            profile: $data['profile'],
            number: $data['number'],
            typeCode: $data['typeCode'],
            issueDate: $data['issueDate'],
            totalAmountWithoutVAT: $data['totalAmountWithoutVAT'],
            totalVATAmount: $data['totalVATAmount'],
            totalAmountWithVAT: $data['totalAmountWithVAT'],
            amountDueForPayment: $data['amountDueForPayment'],
            buyer: Buyer::createFromArray($data['buyer']),
            seller: Seller::createFromArray($data['seller']),
            businessProcessType: $data['businessProcessType'] ?? 'A1',
            currencyCode: $data['currencyCode'] ?? 'EUR',
            vatCurrency: $data['vatCurrency'] ?? 'EUR',
            lineNetAmount: $data['lineNetAmount'] ?? null,
            chargesSum: $data['chargesSum'] ?? null,
            allowancesSum: $data['allowancesSum'] ?? null,
            paidAmount: $data['paidAmount'] ?? null,
            purchaseOrderReference: $data['purchaseOrderReference'] ?? null,
            contractReference: $data['contractReference'] ?? null,
            note: $data['note'] ?? null,
            noteSubjectCode: $data['noteSubjectCode'] ?? NoteSubjectCode::GENERAL_INFORMATION,
            delivery: isset($data['delivery']) ? Delivery::createFromArray($data['delivery']) : null,
            bankAssignedCreditorIdentifier: $data['bankAssignedCreditorIdentifier'] ?? '',
            remittanceInformation: $data['remittanceInformation'] ?? '',
            vatAccountingCurrencyCode: $data['vatAccountingCurrencyCode'] ?? '',
            payee: isset($data['payee']) ? Payee::createFromArray($data['payee']) : null,
            payment: isset($data['payment']) ? Payment::createFromArray($data['payment']) : null,
            vatBreakdowns: isset(($data['vatBreakdowns'])) ? array_map([VatBreakdown::class, 'createFromArray'], $data['vatBreakdowns']) : null,
            invoicingPeriodStartDate: $data['invoicingPeriodStartDate'] ?? null,
            invoicingPeriodEndDate: $data['invoicingPeriodEndDate'] ?? null,
            allowances: isset(($data['allowances'])) ? array_map([Allowance::class, 'createFromArray'], $data['allowances']) : null,
            charges: isset(($data['charges'])) ? array_map([Charge::class, 'createFromArray'], $data['charges']) : null,
            payterm: isset($data['payterm']) ? Payterm::createFromArray($data['payterm']) : null,
            precedingInvoices: $data['precedingInvoices'] ?? null
        );
    }

    public function toXml(): string
    {
        return Builder::build($this);
    }
}
