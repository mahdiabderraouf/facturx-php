<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Models\Address;

class PostalTradeAddress
{
    public static function build(Address $address, Profile $profile): string
    {
        $xml = '<ram:PostalTradeAddress>';

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            if ($address->postCode) {
                $xml .= '<ram:PostcodeCode>' . $address->postCode . '</ram:PostcodeCode>';
            }
            if ($address->address1) {
                $xml .= '<ram:LineOne>' . $address->address1 . '</ram:LineOne>';
            }
            if ($address->address2) {
                $xml .= '<ram:LineTwo>' . $address->address2 . '</ram:LineTwo>';
            }
            if ($address->address3) {
                $xml .= '<ram:LineThree>' . $address->address3 . '</ram:LineThree>';
            }
            if ($address->city) {
                $xml .= '<ram:CityName>' . $address->city . '</ram:CityName>';
            }
        }

        $xml .= '<ram:CountryID>' . $address->countryCode . '</ram:CountryID>';

        if ($profile->isAtLeast(Profile::BASIC_WL)) {
            if ($address->province) {
                $xml .= '<ram:CountrySubDivisionName>' . $address->province . '</ram:CountrySubDivisionName>';
            }
        }

        $xml .= '</ram:PostalTradeAddress>';
        return $xml;
    }
}
