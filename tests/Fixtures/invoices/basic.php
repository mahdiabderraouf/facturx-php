<?php

use MahdiAbderraouf\FacturX\Enums\Profile;
use MahdiAbderraouf\FacturX\Enums\SchemeIdentifier;
use MahdiAbderraouf\FacturX\Enums\Unit;
use MahdiAbderraouf\FacturX\Enums\VatCategory;

$data = require __DIR__ . '/basicwl.php';

$data['profile'] = Profile::BASIC;
$data['paidAmount'] = 0;
$data['lines'] = [
    [
        'identifier' => '001',
        'name' => 'Product A',
        'netPrice' => 100.00,
        'totalNetPrice' => 300.00,
        'invoicedQuantity' => 3,
        'invoicedQuantityUnit' => Unit::ONE,
        'vatCategory' => VatCategory::ZERO_RATED,
    ],
    [
        'identifier' => '002',
        'name' => 'Product B',
        'netPrice' => 60.00,
        'totalNetPrice' => 180.00,
        'invoicedQuantity' => 3,
        'invoicedQuantityUnit' => 'XBX',
        'vatCategory' => VatCategory::STANDARD_RATE,
        'vatRate' => 20.00,
        'grossPrice' => 50.00,
        'priceQuantity' => 1,
        'priceQuantityUnit' => 'XBX',
        'note' => 'Dont 0,50€ d\'éco-participation',
        'standardIdentifier' => '67890',
        'schemeIdentifier' => SchemeIdentifier::GLOBAL_TRADE_ITEM_NUMBER,
    ],
    [
        'identifier' => '003',
        'name' => 'Pack of milk',
        'netPrice' => 60.00,
        'totalNetPrice' => 60.00,
        'invoicedQuantity' => 1,
        'invoicedQuantityUnit' => Unit::GROUP,
        'vatCategory' => VatCategory::STANDARD_RATE,
        'vatRate' => 20.00,
        'grossPrice' => 50.00,
        'priceQuantity' => 6,
        'priceQuantityUnit' => Unit::NUMBER_OF_ARTICLES,
    ],
];

return $data;
