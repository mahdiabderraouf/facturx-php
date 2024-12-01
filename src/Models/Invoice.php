<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class Invoice
{
    private Profile $profile;

    private string $number;
    private string $documentName;
    private int $typeCode;
    private DateTime $issueDate;
    private float $totalAmountWithoutVAT;
    private float $totalVATAmount;
    private float $totalAmountWithVAT;
    private float $amountDueForPayment;
    private string $businessProcessType = 'A1';
    private string $currencyCode = 'EUR';
    private string $vatCurrency = 'EUR';
    private ?string $purchaseOrderReference = null;

    private Buyer $buyer;
    private Seller $seller;

    public function __construct(Profile $profile, array $data)
    {
        $this->profile = $profile;

        $this->number = $data['number'];
        $this->documentName = $data['documentName'];
        $this->typeCode = $data['typeCode'];
        $this->issueDate = $data['issueDate'];
        $this->totalAmountWithoutVAT = $data['totalAmountWithoutVAT'];
        $this->totalVATAmount = $data['totalVATAmount'];
        $this->totalAmountWithVAT = $data['totalAmountWithVAT'];
        $this->amountDueForPayment = $data['amountDueForPayment'];

        $this->buyer = new Buyer($data['buyer']);
        $this->seller = new Buyer($data['seller']);

        if (isset($data['purchaseOrderReference'])) {
            $this->purchaseOrderReference = $data['purchaseOrderReference'];
        }
        if (isset($data['businessProcessType'])) {
            $this->businessProcessType = $data['businessProcessType'];
        }
    }

    public function toXml(): string
    {
        return Builder::build($this);
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): void
    {
        $this->profile = $profile;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    public function setDocumentName(string $documentName): void
    {
        $this->documentName = $documentName;
    }

    public function getTypeCode(): int
    {
        return $this->typeCode;
    }

    public function setTypeCode(int $typeCode): void
    {
        $this->typeCode = $typeCode;
    }

    public function getIssueDate(): DateTime
    {
        return $this->issueDate;
    }

    public function getFormattedIssueDate(): string
    {
        return DateFormat102::toFormat102($this->issueDate);
    }

    public function setIssueDate(DateTime $issueDate): void
    {
        $this->issueDate = $issueDate;
    }

    public function getTotalAmountWithoutVAT(): float
    {
        return $this->totalAmountWithoutVAT;
    }

    public function setTotalAmountWithoutVAT(float $totalAmountWithoutVAT): void
    {
        $this->totalAmountWithoutVAT = $totalAmountWithoutVAT;
    }

    public function getTotalVATAmount(): float
    {
        return $this->totalVATAmount;
    }

    public function setTotalVATAmount(float $totalVATAmount): void
    {
        $this->totalVATAmount = $totalVATAmount;
    }

    public function getTotalAmountWithVAT(): float
    {
        return $this->totalAmountWithVAT;
    }

    public function setTotalAmountWithVAT(float $totalAmountWithVAT): void
    {
        $this->totalAmountWithVAT = $totalAmountWithVAT;
    }

    public function getAmountDueForPayment(): float
    {
        return $this->amountDueForPayment;
    }

    public function setAmountDueForPayment(float $amountDueForPayment): void
    {
        $this->amountDueForPayment = $amountDueForPayment;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getVatCurrency(): string
    {
        return $this->vatCurrency;
    }

    public function setVatCurrency(string $vatCurrency): void
    {
        $this->vatCurrency = $vatCurrency;
    }

    public function getPurchaseOrderReference(): ?string
    {
        return $this->purchaseOrderReference;
    }

    public function setPurchaseOrderReference(?string $purchaseOrderReference): void
    {
        $this->purchaseOrderReference = $purchaseOrderReference;
    }

    public function getBusinessProcessType(): string
    {
        return $this->businessProcessType;
    }

    public function setBusinessProcessType(?string $businessProcessType): void
    {
        $this->businessProcessType = $businessProcessType;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function setBuyer(Buyer $buyer): void
    {
        $this->buyer = $buyer;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function setSeller(Seller $seller): void
    {
        $this->seller = $seller;
    }
}
