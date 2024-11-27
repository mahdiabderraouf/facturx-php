<?php

use MahdiAbderraouf\FacturX\FacturXParser;

require_once __DIR__ . '/../vendor/autoload.php';

$parser = new FacturXParser();

echo htmlspecialchars($parser->getXML(__DIR__ . '/../Facture_F-20240000014.pdf'));
