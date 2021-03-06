<?php

namespace App\Domain;

class BookReference
{
    public function __construct(
        private ?string $reference = null,
    ) {
    }

    public function isEmpty(): bool
    {
        return $this->reference === null;
    }
}
