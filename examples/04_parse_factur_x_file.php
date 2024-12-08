<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MahdiAbderraouf\FacturX\Enums\XmlFilename;
use MahdiAbderraouf\FacturX\Exceptions\UnableToExtractXmlException;
use MahdiAbderraouf\FacturX\Parser;

// PDF path
$pdf = '/path/to/invoice.pdf';

try {
    // this will look for 'factur-x.xml' or 'zugferd-invoice.xml' files inside the given PDF
    $xml = Parser::getXml(
        $pdf,
        XmlFilename::FACTUR_X, // look only for 'factur-x.xml'
    );
} catch (UnableToExtractXmlException $e) {
    // Failed to extract XML from given PDF
    $message = $e->getMessage();
}
