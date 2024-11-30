<?php

use MahdiAbderraouf\FacturX\Enums\AttachmentRelationship;
use MahdiAbderraouf\FacturX\Enums\FacturXProfile;
use MahdiAbderraouf\FacturX\FacturXGenerator;

require_once __DIR__ . '/../vendor/autoload.php';

FacturXGenerator::generate(
    __DIR__ . '/input.pdf',
    __DIR__ . '/factur-x.xml',
    outputPath: __DIR__ . '/out.pdf',
    profile: FacturXProfile::EN16931,
    additionalAttachments: [
        [
            'file' => __DIR__ . '/cgv.txt',
            'filename' => 'cgv.txt',
            'description' => 'This is our CGV',
            'relationship' => AttachmentRelationship::SUPPLEMENT,
        ],
    ]
);
