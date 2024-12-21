<?php

namespace MahdiAbderraouf\FacturX\Models;

use MahdiAbderraouf\FacturX\Enums\NoteSubjectCode;

class Note
{
    public function __construct(
        public string $note,
        public NoteSubjectCode $noteSubjectCode = NoteSubjectCode::GENERAL_INFORMATION,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            note: $data['note'],
            noteSubjectCode: $data['noteSubjectCode'] ?? NoteSubjectCode::GENERAL_INFORMATION
        );
    }
}
