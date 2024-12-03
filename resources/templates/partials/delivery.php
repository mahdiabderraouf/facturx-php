<?php

use MahdiAbderraouf\FacturX\Helpers\DateFormat102;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var bool $isAtLeastBasicWL
 */

$addDeliveryTag = $isAtLeastBasicWL &&
    (
        $invoice->deliverToAddress ||
        $invoice->actualDeliveryDate ||
        $invoice->issuerAssignedID
    );
if ($addDeliveryTag) {
?>
    <ram:ApplicableHeaderTradeDelivery>
        <?php
        if ($invoice->deliverToAddress) {
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
    </ram:ApplicableHeaderTradeDelivery>
<?php
} else {
?>
    <ram:ApplicableHeaderTradeDelivery />
<?php
}
