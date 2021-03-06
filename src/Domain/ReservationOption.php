<?php

namespace App\Domain;

class ReservationOption
{
    /** @var Seat[] */
    private array $seats = [];

    public function __construct(
        private string $trainId,
        private int $seatCount,
    ) {
    }

    public function addSeat(Seat $seat): void
    {
        $this->seats[] = $seat;
    }

    public function isFullfiled(): bool
    {
        return count($this->seats) >= $this->seatCount;
    }

    public function getSeats(): array
    {
        return $this->seats;
    }
}
