<?php

namespace App\Domain;

class Seat
{
    public function __construct(
        private string $coach,
        private int $seatNumber,
    ) {
    }
}
