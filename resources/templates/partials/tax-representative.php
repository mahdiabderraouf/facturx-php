<?php

use MahdiAbderraouf\FacturX\Models\Seller;

/**
 * @var Seller $seller
 * @var bool $isAtLeastBasicWL
 */

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
