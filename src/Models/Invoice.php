<?php

namespace MahdiAbderraouf\FacturX\Models;

use DateTime;
use MahdiAbderraouf\FacturX\Builder;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;

class Invoice
{
    public Profile $profile;

    public string $number;
    public string $documentName;
    public int $typeCode;
    public DateTime $issueDate;
    public float $totalAmountWithoutVAT;
    public float $totalVATAmount;
    public float $totalAmountWithVAT;
    public float $amountDueForPayment;
    public string $businessProcessType = 'A1';
    public string $currencyCode = 'EUR';
    public string $vatCurrency = 'EUR';
    public ?string $purchaseOrderReference = null;
    public ?string $note = null;
    public ?string $noteSubjectCode = null;

    public Buyer $buyer;
    public Seller $seller;

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
        $this->seller = new Seller($data['seller']);

        if (isset($data['purchaseOrderReference'])) {
            $this->purchaseOrderReference = $data['purchaseOrderReference'];
        }
        if (isset($data['businessProcessType'])) {
            $this->businessProcessType = $data['businessProcessType'];
        }
        if (isset($data['note'])) {
            $this->note = $data['note'];
        }
        if (isset($data['noteSubjectCode'])) {
            $this->noteSubjectCode = $data['noteSubjectCode'];
        }
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
