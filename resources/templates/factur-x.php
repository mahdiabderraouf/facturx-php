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

$isAtLeastBasicWL = $profile->isAtLeast(Profile::BASIC_WL);
?>

<rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100" xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <?php
    include __DIR__ . '/partials/header.php';

    include __DIR__ . '/partials/document.php';
    ?>

    <rsm:SupplyChainTradeTransaction>
        <ram:ApplicableHeaderTradeAgreement>
            <?php
            if ($buyer->buyerReference) {
            ?>
                <ram:BuyerReference><?= $buyer->buyerReference; ?></ram:BuyerReference>
            <?php
            }

            include __DIR__ . '/partials/seller.php';

            include __DIR__ . '/partials/buyer.php';

            include __DIR__ . '/partials/tax-representative.php';

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
                    <ram:IssuerAssignedID><?= $seller->contactReference; ?></ram:IssuerAssignedID>
                </ram:ContractReferencedDocument>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeAgreement>

        <?php
        include __DIR__ . '/partials/delivery.php';
        ?>
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
            }
            ?>
            <ram:InvoiceCurrencyCode><?= $invoice->currencyCode; ?></ram:InvoiceCurrencyCode>

            <?php

            include __DIR__ . '/partials/payee.php';

            include __DIR__ . '/partials/payment.php';

            include __DIR__ . '/partials/document-vat.php';

            include __DIR__ . '/partials/invoice-period.php';
            ?>
            <?php

            $allowances = $invoice->allowances;
            include __DIR__ . '/partials/allowances.php';

            $charges = $invoice->charges;
            include __DIR__ . '/partials/allowances.php';

            include __DIR__ . '/partials/payterm.php';

            include __DIR__ . '/partials/document-totals.php';

            include __DIR__ . '/partials/preeceding-invoices.php';

            if ($isAtLeastBasicWL && $buyer->accountingReference) {
            ?>
                <ram:ReceivableSpecifiedTradeAccountingAccount><?= $buyer->accountingReference; ?></ram:ReceivableSpecifiedTradeAccountingAccount>
            <?php
            }
            ?>
        </ram:ApplicableHeaderTradeSettlement>
    </rsm:SupplyChainTradeTransaction>
</rsm:CrossIndustryInvoice>