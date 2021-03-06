<?php

namespace App\Domain;

class SeatWithBookReference
{
    public function __construct(
        private Seat $seat,
        private BookReference $bookReference,
    ) {
    }

    public function getSeat(): Seat
    {
        return $this->seat;
    }

    public function isAvailable(): bool
    {
        return $this->bookReference->isEmpty();
    }
}
