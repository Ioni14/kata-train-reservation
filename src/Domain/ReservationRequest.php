<?php

namespace App\Domain;

class ReservationRequest
{
    public function __construct(
        public string $trainId,
        public int $seatCount,
    ) {
    }
}
