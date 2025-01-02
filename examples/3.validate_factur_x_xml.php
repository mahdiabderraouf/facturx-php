<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Exceptions\InvalidXmlException;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Validator;

$profile = Profile::MINIMUM;

// PDF path, XML path or XML string
$pdf = '/path/to/invoice.pdf';

try {
    Validator::validate(
        $pdf,
        // You can precise the wanted profile
        Profile::EN16931,
    );
} catch (UnableToExtractXmlException $e) {
    // Failed to extract XML from given PDF
    $message = $e->getMessage();
} catch (InvalidXmlException $e) {
    // array of LibXMLError
    $errors = $e->getErrors();
}

// Sometimes we want to only check if its valid or not
$isValid = Validator::isValid($pdf);

echo $isValid ?
    'File ' . $pdf . ' is Factur-X Valid' :
    'File ' . $pdf . ' is not Factur-X Valid';
