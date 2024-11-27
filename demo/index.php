<?php

use MahdiAbderraouf\FacturX\FacturXParser;
use MahdiAbderraouf\FacturX\FacturXValidator;

require_once __DIR__ . '/../vendor/autoload.php';

// $parser = new FacturXParser();

// echo htmlspecialchars($parser->getXML(__DIR__ . '/../Facture_F-20240000014.pdf'));

echo FacturXValidator::validate(__DIR__ . '/../noprofile.pdf');