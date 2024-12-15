<?php

namespace MahdiAbderraouf\FacturX\Builders;

use MahdiAbderraouf\FacturX\Models\Address;

class PostalTradeAddress
{
    public static function build(Address $address, bool $isAtLeastBasicWl = false): string
    {
        $xml = '<ram:PostalTradeAddress>';
        $xml .= '<ram:CountryID>' . $address->countryCode . '</ram:CountryID>';

        if ($isAtLeastBasicWl) {
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
            if ($address->province) {
                $xml .= '<ram:CountrySubDivisionName>' . $address->province . '</ram:CountrySubDivisionName>';
            }
        }

        $xml .= '</ram:PostalTradeAddress>';
        return $xml;
    }
}
