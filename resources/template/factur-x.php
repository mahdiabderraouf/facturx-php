<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Seller;

/**
 * @var Invoice $invoice
 * @var Buyer $buyer
 * @var Seller $seller
 * @var Profile $profile
 */
?>

<rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100" xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <rsm:ExchangedDocumentContext>
        <ram:BusinessProcessSpecifiedDocumentContextParameter>
            <ram:ID><?= $invoice->businessProcessType; ?></ram:ID>
        </ram:BusinessProcessSpecifiedDocumentContextParameter>
        <ram:GuidelineSpecifiedDocumentContextParameter>
            <ram:ID><?= $profile->value; ?></ram:ID>
        </ram:GuidelineSpecifiedDocumentContextParameter>
    </rsm:ExchangedDocumentContext>
    <rsm:ExchangedDocument>
        <ram:ID><?= $invoice->number; ?></ram:ID>
        <ram:TypeCode><?= $invoice->typeCode; ?></ram:TypeCode>
        <ram:IssueDateTime>
            <udt:DateTimeString format="102"><?= $invoice->getFormattedIssueDate(); ?></udt:DateTimeString>
        </ram:IssueDateTime>
        <?php
        if ($profile->isAtLeast(Profile::BASIC_WL) && $invoice->note) {
        ?>
            <ram:IncludedNote>
                <ram:Content><?= $invoice->note; ?></ram:Content>
                <ram:SubjectCode><?= $invoice->noteSubjectCode; ?><< /ram:SubjectCode>
            </ram:IncludedNote>
        <?php
        }
        ?>
    </rsm:ExchangedDocument>
    <rsm:SupplyChainTradeTransaction>
        <ram:ApplicableHeaderTradeAgreement>
            <?php
            if ($buyer->buyerReference) {
            ?>
                <ram:BuyerReference><?= $buyer->buyerReference; ?></ram:BuyerReference>
            <?php
            }
            ?>
            <ram:SellerTradeParty>
                <?php

                if ($profile->isAtLeast(Profile::BASIC_WL)) {
                    foreach ($seller->identifiers as $identifier) {
                ?>
                        <ram:ID><?= $identifier; ?></ram:ID>
                    <?php
                    }
                    foreach ($seller->globalIndetifiers as $globalIdentifier) {
                    ?>
                        <ram:GlobalID schemeID="<?= $globalIdentifier['schemeIdentifier']; ?>"><?= $globalIdentifier['identifier']; ?></ram:GlobalID>
                <?php
                    }
                }
                ?>
                <ram:Name><?= $seller->name; ?></ram:Name>
                <?php
                $addTradingName = $seller->tradingName && $profile->isAtLeast(Profile::BASIC_WL);
                if ($seller->legalRegistrationIdentifier || $addTradingName) {
                ?>
                    <ram:SpecifiedLegalOrganization>
                        <?php
                        if ($seller->legalRegistrationIdentifier) {
                        ?>
                            <ram:ID schemeID="<?= $seller->schemeIdentifier; ?>">
                                <?= $seller->legalRegistrationIdentifier; ?>
                            </ram:ID>
                        <?php
                        }
                        if ($addTradingName) {
                        ?>
                            <ram:TradingBusinessName>
                                <?= $seller->tradingName; ?>
                            </ram:TradingBusinessName>
                        <?php
                        }
                        ?>
                    </ram:SpecifiedLegalOrganization>
                <?php
                }
                ?>
                <ram:PostalTradeAddress>
                    <ram:CountryID><?= $seller->countryCode; ?></ram:CountryID>
                </ram:PostalTradeAddress>
                <ram:SpecifiedTaxRegistration>
                    <ram:ID schemeID="VA"><?= $seller->vatIndetifier; ?></ram:ID>
                </ram:SpecifiedTaxRegistration>
            </ram:SellerTradeParty>
            <ram:BuyerTradeParty>
                <ram:Name><?= $buyer->name; ?></ram:Name>
                <?php
                if ($buyer->legalRegistrationIdentifier) {
                ?>
                    <ram:SpecifiedLegalOrganization>
                        <ram:ID schemeID="<?= $buyer->schemeIdentifier; ?>"><?= $buyer->legalRegistrationIdentifier; ?></ram:ID>
                    </ram:SpecifiedLegalOrganization>
                <?php
                }
                ?>
            </ram:BuyerTradeParty>
            <?php
            if ($invoice->purchaseOrderReference) {
            ?>
                <ram:BuyerOrderReferencedDocument>
                    <ram:IssuerAssignedID><?= $invoice->purchaseOrderReference; ?></ram:IssuerAssignedID>
                </ram:BuyerOrderReferencedDocument>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeAgreement>
        <ram:ApplicableHeaderTradeDelivery />
        <ram:ApplicableHeaderTradeSettlement>
            <ram:InvoiceCurrencyCode><?= $invoice->currencyCode; ?></ram:InvoiceCurrencyCode>
            <ram:SpecifiedTradeSettlementHeaderMonetarySummation>
                <ram:TaxBasisTotalAmount><?= $invoice->totalAmountWithoutVAT; ?></ram:TaxBasisTotalAmount>
                <ram:TaxTotalAmount currencyID="<?= $invoice->vatCurrency; ?>">
                    <?= $invoice->totalVATAmount; ?>
                </ram:TaxTotalAmount>
                <ram:GrandTotalAmount><?= $invoice->totalAmountWithVAT; ?></ram:GrandTotalAmount>

                <ram:DuePayableAmount><?= $invoice->amountDueForPayment; ?></ram:DuePayableAmount>
            </ram:SpecifiedTradeSettlementHeaderMonetarySummation>
        </ram:ApplicableHeaderTradeSettlement>
    </rsm:SupplyChainTradeTransaction>
</rsm:CrossIndustryInvoice>