<?php

use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Seller;

/**
 * @var Invoice $invoice
 * @var Buyer $buyer
 * @var Seller $seller
 */
?>

<rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100" xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <rsm:ExchangedDocumentContext>
        <ram:BusinessProcessSpecifiedDocumentContextParameter>
            <ram:ID><?= $invoice->getBusinessProcessType(); ?></ram:ID>
        </ram:BusinessProcessSpecifiedDocumentContextParameter>
        <ram:GuidelineSpecifiedDocumentContextParameter>
            <ram:ID><?= $invoice->getProfile()->value; ?></ram:ID>
        </ram:GuidelineSpecifiedDocumentContextParameter>
    </rsm:ExchangedDocumentContext>
    <rsm:ExchangedDocument>
        <ram:ID><?= $invoice->getNumber(); ?></ram:ID>
        <ram:TypeCode><?= $invoice->getTypeCode(); ?></ram:TypeCode>
        <ram:IssueDateTime>
            <udt:DateTimeString format="102"><?= $invoice->getFormattedIssueDate(); ?></udt:DateTimeString>
        </ram:IssueDateTime>
    </rsm:ExchangedDocument>
    <rsm:SupplyChainTradeTransaction>
        <ram:ApplicableHeaderTradeAgreement>
            <?php
            if ($buyerReference = $buyer->getBuyerReference()) {
            ?>
                <ram:BuyerReference><?= $buyerReference; ?></ram:BuyerReference>
            <?php
            }
            ?>
            <ram:SellerTradeParty>
                <ram:Name><?= $seller->getName(); ?></ram:Name>
                <?php
                if ($sellerLegalRegistrationIdentifier = $seller->getLegalRegistrationIdentifier()) {
                ?>
                    <ram:SpecifiedLegalOrganization>
                        <ram:ID schemeID="<?= $seller->getSchemeIdentifier(); ?>">
                            <?= $sellerLegalRegistrationIdentifier; ?>
                        </ram:ID>
                    </ram:SpecifiedLegalOrganization>
                <?php
                }
                ?>
                <ram:PostalTradeAddress>
                    <ram:CountryID><?= $seller->getCountryCode(); ?></ram:CountryID>
                </ram:PostalTradeAddress>
                <ram:SpecifiedTaxRegistration>
                    <ram:ID schemeID="<?= $seller->getTaxSchemeIdentifier(); ?>"><?= $seller->getVatIdentifier(); ?></ram:ID>
                </ram:SpecifiedTaxRegistration>
            </ram:SellerTradeParty>
            <ram:BuyerTradeParty>
                <ram:Name><?= $buyer->getName(); ?></ram:Name>
                <ram:SpecifiedLegalOrganization>
                    <ram:ID schemeID="<?= $buyer->getSchemeIdentifier(); ?>"><?= $buyer->getLegalRegistrationIdentifier(); ?></ram:ID>
                </ram:SpecifiedLegalOrganization>
            </ram:BuyerTradeParty>
            <?php
            if ($invoice->getPurchaseOrderReference()) {
            ?>
                <ram:BuyerOrderReferencedDocument>
                    <ram:IssuerAssignedID><?= $invoice->getPurchaseOrderReference(); ?></ram:IssuerAssignedID>
                </ram:BuyerOrderReferencedDocument>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeAgreement>
        <ram:ApplicableHeaderTradeDelivery />
        <ram:ApplicableHeaderTradeSettlement>
            <ram:InvoiceCurrencyCode><?= $invoice->getCurrencyCode(); ?></ram:InvoiceCurrencyCode>
            <ram:SpecifiedTradeSettlementHeaderMonetarySummation>
                <ram:TaxBasisTotalAmount><?= $invoice->getTotalAmountWithoutVAT(); ?></ram:TaxBasisTotalAmount>
                <ram:TaxTotalAmount currencyID="<?= $invoice->getVatCurrency(); ?>">
                    <?= $invoice->getTotalVATAmount(); ?>
                </ram:TaxTotalAmount>
                <ram:GrandTotalAmount><?= $invoice->getTotalAmountWithVAT(); ?></ram:GrandTotalAmount>

                <ram:DuePayableAmount><?= $invoice->getAmountDueForPayment(); ?></ram:DuePayableAmount>
            </ram:SpecifiedTradeSettlementHeaderMonetarySummation>
        </ram:ApplicableHeaderTradeSettlement>
    </rsm:SupplyChainTradeTransaction>
</rsm:CrossIndustryInvoice>