<?php

namespace MahdiAbderraouf\FacturX\Models;

use InvalidArgumentException;

class Address
{
    public function __construct(
        public string $countryCode,
        public string $postCode = '',
        public string $address1 = '',
        public string $address2 = '',
        public string $address3 = '',
        public string $city = '',
        public string $province = ''
    ) {
        if (strlen($countryCode) !== 2) {
            throw new InvalidArgumentException('$countryCode must be an ISO-2 country code');
        }
        $this->countryCode = strtoupper($countryCode);
    }
}
