<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Generator;
use MahdiAbderraouf\FacturX\Models\Invoice;

require_once __DIR__ . '/../vendor/autoload.php';

echo (Profile::EN16931)->isAtLeast(Profile::EXTENDED) ? 'yes' : 'no';

// Generator::generate(
//     __DIR__ . '/input.pdf',
//     new Invoice(Profile::MINIMUM, [
//         'number' => 'test',
//         'documentName' => 'test',
//         'typeCode' => 380,
//         'invoiceIssueDate' => new DateTime(),
//         'totalAmountWithoutVAT' => 20.00,
//         'totalVATAmount' => 20.00,
//         'totalAmountWithVAT' => 20.00,
//         'amountDueForPayment' => 20.00,
//         'buyer' => [
//             ''
//         ]
//     ]),
//     outputPath: __DIR__ . '/minimum.pdf'
// );
