<?php

use MahdiAbderraouf\FacturX\Enums\FacturXProfile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\FacturXParser;
use MahdiAbderraouf\FacturX\FacturXValidator;

require_once __DIR__ . '/../vendor/autoload.php';

// $parser = new FacturXParser();

// echo htmlspecialchars($parser->getXml(__DIR__ . '/../Facture_F-20240000014.pdf'));

try {
    FacturXValidator::validate(__DIR__ . '/../Facture_F-20240000014.pdf', FacturXProfile::BASIC);
} catch (InvalidXmlException $e) {
    print_r($e->getErrors());
}