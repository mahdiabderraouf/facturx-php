<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Helpers\Utils;
use MahdiAbderraouf\FacturX\Models\Invoice;
use MahdiAbderraouf\FacturX\Models\Seller;

/**
 * @var Invoice $invoice
 * @var Seller $seller
 * @var Profile $profile
 * @var bool $isAtLeastBasicWL
 */
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
                <ram:ID schemeID="<?= $seller->schemeIdentifier; ?>"><?= $seller->legalRegistrationIdentifier; ?></ram:ID>
            <?php
            }
            if ($addTradingName) {
            ?>
                <ram:TradingBusinessName><?= $seller->tradingName; ?></ram:TradingBusinessName>
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