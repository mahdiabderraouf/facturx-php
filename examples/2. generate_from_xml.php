<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Generator;

$profile = Profile::MINIMUM;

// path or xml string
$xml = '/path/to/factur-x.xml';

try {
    // XML will be validated against the auto-detected profile
    $pdfString = Generator::generate(
        // path or PDF string
        '/path/to/Invoice.pdf',
        $xml
    );
} catch (InvalidXmlException $invalidXmlException) {
    $errors = $invalidXmlException->getErrors();
}
