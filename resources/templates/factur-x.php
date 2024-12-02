<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Seller;

/**
 * @var Invoice $invoice
 * @var Buyer $buyer
 * @var Seller $seller
 * @var Profile $profile
 */

$isAtLeastBasicWL = $profile->isAtLeast(Profile::BASIC_WL);
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
            <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->issueDate); ?></udt:DateTimeString>
        </ram:IssueDateTime>
        <?php
        if ($isAtLeastBasicWL && $invoice->note) {
        ?>
            <ram:IncludedNote>
                <ram:Content><?= $invoice->note; ?></ram:Content>
                <ram:SubjectCode><?= $invoice->noteSubjectCode; ?></ram:SubjectCode>
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

                if ($isAtLeastBasicWL) {
                    foreach ($seller->identifiers as $identifier) {
                ?>
                        <ram:ID><?= $identifier; ?></ram:ID>
                    <?php
                    }
                    foreach ($seller->globalIndetifiers as $globalIdentifier) {
                    ?>
                        <ram:GlobalID schemeID="<?= Utils::stringOrEnumToString($globalIdentifier['schemeIdentifier']); ?>"><?= $globalIdentifier['identifier']; ?></ram:GlobalID>
                <?php
                    }
                }
                ?>
                <ram:Name><?= $seller->name; ?></ram:Name>
                <?php
                $addTradingName = $seller->tradingName && $isAtLeastBasicWL;
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
                    <ram:CountryID><?= $seller->address->countryCode; ?></ram:CountryID>
                    <?php
                    if ($isAtLeastBasicWL) {

                        if ($seller->address->postCode) {
                    ?>
                            <ram:PostcodeCode><?= $seller->address->postCode; ?></ram:PostcodeCode>
                        <?php
                        }
                        if ($seller->address->address1) {
                        ?>
                            <ram:LineOne><?= $seller->address->address1; ?></ram:LineOne>
                        <?php
                        }
                        if ($seller->address->address2) {
                        ?>
                            <ram:LineTwo><?= $seller->address->address2; ?></ram:LineTwo>
                        <?php
                        }
                        if ($seller->address->address3) {
                        ?>
                            <ram:LineThree><?= $seller->address->address3; ?></ram:LineThree>
                        <?php
                        }
                        if ($seller->address->city) {
                        ?>
                            <ram:CityName><?= $seller->address->city; ?></ram:CityName>
                        <?php
                        }
                        if ($seller->address->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $seller->address->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        if ($seller->address->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $seller->address->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        if ($seller->email) {
                        ?>
                            <ram:URIUniversalCommunication>
                                <ram:URIId schemeID="<?= SchemeIdentifier::EMAIL; ?>"><?= $seller->email; ?></ram:URIId>
                            </ram:URIUniversalCommunication>
                    <?php
                        }
                    }
                    ?>
                </ram:PostalTradeAddress>
                <ram:SpecifiedTaxRegistration>
                    <ram:ID schemeID="VA"><?= $seller->vatIdentifier; ?></ram:ID>
                </ram:SpecifiedTaxRegistration>
            </ram:SellerTradeParty>
            <ram:BuyerTradeParty>
                <?php

                if ($isAtLeastBasicWL) {
                    foreach ($buyer->identifiers as $identifier) {
                ?>
                        <ram:ID><?= $identifier; ?></ram:ID>
                    <?php
                    }
                    foreach ($buyer->globalIndetifiers as $globalIdentifier) {
                    ?>
                        <ram:GlobalID schemeID="<?= Utils::stringOrEnumToString($globalIdentifier['schemeIdentifier']); ?>"><?= $globalIdentifier['identifier']; ?></ram:GlobalID>
                <?php
                    }
                }
                ?>
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
                <?php
                if ($isAtLeastBasicWL) {
                ?>
                    <ram:PostalTradeAddress>
                        <ram:CountryID><?= $buyer->address->countryCode; ?></ram:CountryID>
                        <?php
                        if ($buyer->address->postCode) {
                        ?>
                            <ram:PostcodeCode><?= $buyer->address->postCode; ?></ram:PostcodeCode>
                        <?php
                        }
                        if ($buyer->address->address1) {
                        ?>
                            <ram:LineOne><?= $buyer->address->address1; ?></ram:LineOne>
                        <?php
                        }
                        if ($buyer->address->address2) {
                        ?>
                            <ram:LineTwo><?= $buyer->address->address2; ?></ram:LineTwo>
                        <?php
                        }
                        if ($buyer->address->address3) {
                        ?>
                            <ram:LineThree><?= $buyer->address->address3; ?></ram:LineThree>
                        <?php
                        }
                        if ($buyer->address->city) {
                        ?>
                            <ram:CityName><?= $buyer->address->city; ?></ram:CityName>
                        <?php
                        }
                        if ($buyer->address->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $buyer->address->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        if ($buyer->address->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $buyer->address->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        ?>
                    </ram:PostalTradeAddress>
                    <?php
                    if ($seller->email) {
                    ?>
                        <ram:URIUniversalCommunication>
                            <ram:URIId schemeID="<?= SchemeIdentifier::EMAIL; ?>"><?= $seller->email; ?></ram:URIId>
                        </ram:URIUniversalCommunication>
                    <?php
                    }
                    if ($buyer->vatIdentifier) {
                    ?>
                        <ram:SpecifiedTaxRegistration>
                            <ram:ID schemeID="VA"><?= $buyer->vatIdentifier; ?></ram:ID>
                        </ram:SpecifiedTaxRegistration>
                <?php
                    }
                }
                ?>
            </ram:BuyerTradeParty>
            <?php
            if ($isAtLeastBasicWL && $seller->taxRepresentativeName) {
            ?>
                <ram:SellerTaxRepresentativeTradeParty>
                    <ram:Name><?= $seller->taxRepresentativeName; ?></ram:Name>
                    <ram:PostalTradeAddress>
                        <ram:CountryID><?= $seller->taxRepresentativeaddress->countryCode; ?></ram:CountryID>
                        <?php
                        if ($seller->taxRepresentativeaddress->postCode) {
                        ?>
                            <ram:PostcodeCode><?= $seller->taxRepresentativeaddress->postCode; ?></ram:PostcodeCode>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->address1) {
                        ?>
                            <ram:LineOne><?= $seller->taxRepresentativeaddress->address1; ?></ram:LineOne>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->address2) {
                        ?>
                            <ram:LineTwo><?= $seller->taxRepresentativeaddress->address2; ?></ram:LineTwo>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->address3) {
                        ?>
                            <ram:LineThree><?= $seller->taxRepresentativeaddress->address3; ?></ram:LineThree>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->city) {
                        ?>
                            <ram:CityName><?= $seller->taxRepresentativeaddress->city; ?></ram:CityName>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $seller->taxRepresentativeaddress->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        if ($seller->taxRepresentativeaddress->province) {
                        ?>
                            <ram:CountrySubDivisionName><?= $seller->taxRepresentativeaddress->province; ?></ram:CountrySubDivisionName>
                        <?php
                        }
                        ?>
                    </ram:PostalTradeAddress>
                    <ram:SpecifiedTaxRegistration>
                        <ram:ID schemeID="VA"><?= $seller->taxRepresentativeVatIdentifier; ?></ram:ID>
                    </ram:SpecifiedTaxRegistration>
                </ram:SellerTaxRepresentativeTradeParty>
            <?php
            }

            if ($invoice->purchaseOrderReference) {
            ?>
                <ram:BuyerOrderReferencedDocument>
                    <ram:IssuerAssignedID><?= $invoice->purchaseOrderReference; ?></ram:IssuerAssignedID>
                </ram:BuyerOrderReferencedDocument>
            <?php
            }

            if ($isAtLeastBasicWL && $seller->contactReference) {
            ?>
                <ram:ContractReferencedDocument>
                    <ram:IssuerAssignedID></ram:IssuerAssignedID>
                </ram:ContractReferencedDocument>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeAgreement>
        <ram:ApplicableHeaderTradeDelivery>
            <?php
            if ($isAtLeastBasicWL) {
                $addShipToTradeParty = $invoice->deliverToLocationIdentifier ||
                    $invoice->deliverToLocationGlobalIdentifier ||
                    $invoice->deliverToPartyName ||
                    $invoice->deliverToAddress;
                if ($addShipToTradeParty) {
            ?>
                    <ram:ShipToTradeParty>
                        <?php
                        if ($invoice->deliverToLocationIdentifier) {
                        ?>
                            <ram:ID><?= $invoice->deliverToLocationIdentifier; ?></ram:ID>
                        <?php
                        }
                        if ($invoice->deliverToLocationGlobalIdentifier) {
                        ?>
                            <ram:GlobalID schemeID="<?= $invoice->deliverToLocationGlobalIdentifierSchemeIdentifier->value; ?>"><?= $invoice->deliverToLocationGlobalIdentifier; ?></ram:GlobalID>
                        <?php
                        }
                        if ($invoice->deliverToPartyName) {
                        ?>
                            <ram:Name><?= $invoice->deliverToPartyName; ?></ram:Name>
                        <?php
                        }
                        if ($invoice->deliverToAddress) {
                        ?>
                            <ram:PostalTradeAddress>
                                <ram:CountryID><?= $invoice->deliverToAddress->countryCode; ?></ram:CountryID>
                                <?php
                                if ($invoice->deliverToAddress->postCode) {
                                ?>
                                    <ram:PostcodeCode><?= $invoice->deliverToAddress->postCode; ?></ram:PostcodeCode>
                                <?php
                                }
                                if ($invoice->deliverToAddress->address1) {
                                ?>
                                    <ram:LineOne><?= $invoice->deliverToAddress->address1; ?></ram:LineOne>
                                <?php
                                }
                                if ($invoice->deliverToAddress->address2) {
                                ?>
                                    <ram:LineTwo><?= $invoice->deliverToAddress->address2; ?></ram:LineTwo>
                                <?php
                                }
                                if ($invoice->deliverToAddress->address3) {
                                ?>
                                    <ram:LineThree><?= $invoice->deliverToAddress->address3; ?></ram:LineThree>
                                <?php
                                }
                                if ($invoice->deliverToAddress->city) {
                                ?>
                                    <ram:CityName><?= $invoice->deliverToAddress->city; ?></ram:CityName>
                                <?php
                                }
                                if ($invoice->deliverToAddress->province) {
                                ?>
                                    <ram:CountrySubDivisionName><?= $invoice->deliverToAddress->province; ?></ram:CountrySubDivisionName>
                                <?php
                                }
                                if ($invoice->deliverToAddress->province) {
                                ?>
                                    <ram:CountrySubDivisionName><?= $invoice->deliverToAddress->province; ?></ram:CountrySubDivisionName>
                                <?php
                                }
                                ?>
                            </ram:PostalTradeAddress>
                        <?php
                        }
                        ?>
                    </ram:ShipToTradeParty>
                <?php
                }
                if ($invoice->actualDeliveryDate) {
                ?>
                    <ram:ActualDeliverySupplyChainEvent>
                        <ram:OccurrenceDateTime>
                            <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->actualDeliveryDate); ?></udt:DateTimeString>
                        </ram:OccurrenceDateTime>
                    </ram:ActualDeliverySupplyChainEvent>
                <?php
                }
                if ($invoice->issuerAssignedID) {
                ?>
                    <ram:DespatchAdviceReferencedDocument>
                        <udt:IssuerAssignedID><?= $invoice->issuerAssignedID; ?></udt:IssuerAssignedID>
                    </ram:DespatchAdviceReferencedDocument>
                <?php
                }
                ?>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeDelivery>
        <ram:ApplicableHeaderTradeSettlement>
            <?php
            if ($isAtLeastBasicWL) {
                if ($invoice->bankAssignedCreditorIdentifier) {
            ?>
                    <ram:CreditorReferenceID><?= $invoice->bankAssignedCreditorIdentifier; ?></ram:CreditorReferenceID>
                <?php

                }
                if ($invoice->remittanceInformation) {
                ?>
                    <ram:PaymentReference><?= $invoice->remittanceInformation; ?></ram:PaymentReference>
                <?php
                }
                if ($invoice->vatAccountingCurrencyCode) {
                ?>
                    <ram:TaxCurrencyCode><?= $invoice->vatAccountingCurrencyCode; ?></ram:TaxCurrencyCode>
                <?php
                }
                if ($invoice->payee) {
                ?>
                    <ram:PayeeTradeParty>
                        <?php
                        if ($invoice->payee->identifier) {
                        ?>
                            <ram:ID><?= $invoice->payee->identifier; ?></ram:ID>
                        <?php
                        }
                        if ($invoice->payee->globalIdentifier) {
                        ?>
                            <ram:globalID schemeID="<?= $invoice->payee->globalIdentifierSchemeIdentifier; ?>"><?= $invoice->payee->globalIdentifier; ?></ram:globalID>
                        <?php
                        }
                        ?>
                        <ram:Name><?= $invoice->payee->name; ?></ram:Name>
                        <?php

                        if ($invoice->payee->legalRegistrationIdentifier) {
                        ?>
                            <ram:SpecifiedLegalOrganization>
                                <ram:ID schemeID="<?= $invoice->payee->legalRegistrationSchemeIdentifier; ?>"><?= $invoice->payee->legalRegistrationIdentifier; ?></ram:ID>
                            </ram:SpecifiedLegalOrganization>
                        <?php
                        }
                        ?>
                    </ram:PayeeTradeParty>
                <?php
                }
                if ($invoice->payment) {
                ?>
                    <ram:SpecifiedTradeSettlementPaymentMeans>
                        <ram:TypeCode><?= $invoice->payment->paymentMeansTypeCode; ?></ram:TypeCode>
                        <?php
                        if ($invoice->payment->debitedAccountIdentifier) {
                        ?>
                            <ram:PayerPartyDebtorFinancialAccount>
                                <ram:IBANID><?= $invoice->payment->paymentMeansTypeCode; ?></ram:IBANID>
                            </ram:PayerPartyDebtorFinancialAccount>
                        <?php
                        }
                        if ($invoice->payment->paymentAccountIdentifier || $invoice->payment->nationalAccountNumber) {
                        ?>
                            <ram:PayeePartyCreditorFinancialAccount>
                                <?php

                                if ($invoice->payment->debitedAccountIdentifier) {
                                ?>
                                    <ram:IBANID><?= $invoice->payment->debitedAccountIdentifier; ?></ram:IBANID>
                                <?php
                                }
                                if ($invoice->payment->nationalAccountNumber) {
                                ?>
                                    <ram:ProprietaryID><?= $invoice->payment->nationalAccountNumber; ?></ram:ProprietaryID>
                                <?php
                                }
                                ?>
                            </ram:PayeePartyCreditorFinancialAccount>
                        <?php
                        }
                        ?>
                    </ram:SpecifiedTradeSettlementPaymentMeans>
                <?php
                }
                ?>
                <ram:ApplicableTradeTax>
                    <?php
                    foreach ($invoice->vatBreakdowns as $vatBreakdown) {
                    ?>
                        <ram:CalculatedAmount><?= $vatBreakdown->vatCategoryTaxAmount; ?></ram:CalculatedAmount>
                        <ram:TypeCode>VAT</ram:TypeCode>

                        <?php
                        if ($vatBreakdown->exemptionReason) {
                        ?>
                            <ram:ExemptionReason><?= $vatBreakdown->exemptionReason; ?></ram:ExemptionReason>
                        <?php
                        }
                        ?>
                        <ram:BasisAmount><?= $vatBreakdown->vatCategoryTaxableAmount; ?></ram:BasisAmount>
                        <ram:CategoryCode><?= $vatBreakdown->vatCategory->value; ?></ram:CategoryCode>
                        <?php
                        if ($vatBreakdown->vatExemptionReasonCode) {
                        ?>
                            <ram:ExemptionReasonCode><?= $vatBreakdown->vatExemptionReasonCode; ?></ram:ExemptionReasonCode>
                        <?php
                        }
                        if ($vatBreakdown->valueAddedTaxPointDateCode) {
                        ?>
                            <ram:DueDateTypeCode><?= $vatBreakdown->valueAddedTaxPointDateCode; ?></ram:DueDateTypeCode>
                        <?php
                        }
                        if ($vatBreakdown->percentage) {
                        ?>
                            <ram:RateApplicablePercent><?= $vatBreakdown->percentage; ?></ram:RateApplicablePercent>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                </ram:ApplicableTradeTax>
                <?php
                if ($invoice->invoicingPeriodStartDate || $invoice->invoicingPeriodEndDate) {
                ?>
                    <ram:BillingSpecifiedPeriod>
                        <?php
                        if ($invoice->invoicingPeriodStartDate) {
                        ?>
                            ?>
                            <ram:StartDateTime>
                                <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->invoicingPeriodStartDate); ?></udt:DateTimeString>
                            </ram:StartDateTime>
                        <?php
                        }

                        if ($invoice->invoicingPeriodEndDate) {
                        ?>
                            <ram:EndDateTime>
                                <udt:DateTimeString format="102"><?= DateFormat102::toFormat102($invoice->invoicingPeriodEndDate); ?></udt:DateTimeString>
                            </ram:EndDateTime>
                    <?php
                        }
                    }
                    ?>
                    </ram:BillingSpecifiedPeriod>
                <?php
            }
                ?>
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