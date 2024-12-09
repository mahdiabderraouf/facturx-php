<?php

namespace MahdiAbderraouf\FacturX;

use DOMDocument;
use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Invoice;

class Builder
{
    public Invoice $invoice;
    public string $xml = '';
    public bool $isAtLeastBasicWL;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->isAtLeastBasicWL = $invoice->profile->isAtLeast(Profile::BASIC_WL);
    }

    public function build(): string
    {
        $this->createExchangedDocumentContext();
        $this->createExchangedDocument();

        $this->wrapWithCrossIndustryInvoiceTag();

        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;

        $domDocument->loadXML($this->xml);

        return $domDocument->saveXML();
    }

    private function wrapWithCrossIndustryInvoiceTag(): void
    {
        $this->xml = <<<XML
        <rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100"
            xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100"
            xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100"
            xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            {$this->xml}
        </rsm:CrossIndustryInvoice>
        XML;
    }

    private function createExchangedDocumentContext(): void
    {
        $this->xml .= (<<<XML
        <rsm:ExchangedDocumentContext>
            <ram:BusinessProcessSpecifiedDocumentContextParameter>
                <ram:ID>{$this->invoice->businessProcessType}</ram:ID>
            </ram:BusinessProcessSpecifiedDocumentContextParameter>
            <ram:GuidelineSpecifiedDocumentContextParameter>
                <ram:ID>{$this->invoice->profile->value}</ram:ID>
            </ram:GuidelineSpecifiedDocumentContextParameter>
        </rsm:ExchangedDocumentContext>
        XML);
    }

    private function createExchangedDocument(): void
    {
        $xml = <<<XML
        <ram:ID>{$this->invoice->number}</ram:ID>
        <ram:TypeCode>{$this->invoice->typeCode}</ram:TypeCode>
        <ram:IssueDateTime>
            <udt:DateTimeString format="102">{DateFormat102::toFormat102($this->invoice->issueDate})</udt:DateTimeString>
        </ram:IssueDateTime>
        XML;

        if ($this->isAtLeastBasicWL && $this->invoice->note) {
            $xml .= <<<XML
            <ram:IncludedNote>
                <ram:Content>{$this->invoice->note}</ram:Content>
                <ram:SubjectCode>{$this->invoice->noteSubjectCode}</ram:SubjectCode>
            </ram:IncludedNote>
            XML;
        }

        $this->xml .= $xml;
    }
}
