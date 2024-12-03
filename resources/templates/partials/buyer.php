<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Models\Buyer;
use MahdiAbderraouf\FacturX\Models\Invoice;

/**
 * @var Invoice $invoice
 * @var Buyer $buyer
 * @var Profile $profile
 * @var bool $isAtLeastBasicWL
 */
?>

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