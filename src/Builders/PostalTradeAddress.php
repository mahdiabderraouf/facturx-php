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
            if ($address->postCode !== '' && $address->postCode !== '0') {
                $xml .= '<ram:PostcodeCode>' . $address->postCode . '</ram:PostcodeCode>';
            }

            if ($address->address1 !== '' && $address->address1 !== '0') {
                $xml .= '<ram:LineOne>' . $address->address1 . '</ram:LineOne>';
            }

            if ($address->address2 !== '' && $address->address2 !== '0') {
                $xml .= '<ram:LineTwo>' . $address->address2 . '</ram:LineTwo>';
            }

            if ($address->address3 !== '' && $address->address3 !== '0') {
                $xml .= '<ram:LineThree>' . $address->address3 . '</ram:LineThree>';
            }

            if ($address->city !== '' && $address->city !== '0') {
                $xml .= '<ram:CityName>' . $address->city . '</ram:CityName>';
            }
        }

        $xml .= '<ram:CountryID>' . $address->countryCode . '</ram:CountryID>';

        if ($profile->isAtLeast(Profile::BASIC_WL) && $address->province) {
            $xml .= '<ram:CountrySubDivisionName>' . $address->province . '</ram:CountrySubDivisionName>';
        }
        return $xml . '</ram:PostalTradeAddress>';
    }
}
