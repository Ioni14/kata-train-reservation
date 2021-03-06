<?php

namespace App\Domain;

use Webmozart\Assert\Assert;

class Train
{
    private const RESERVATION_MAX_THRESHOLD_PERCENT = 0.7;

    /**
     * @param SeatWithBookReference[] $seats
     */
    public function __construct(
        private string $id,
        private array $seats,
    ) {
        /** @var iterable $seats */
        Assert::allIsInstanceOf($seats, SeatWithBookReference::class);
    }

    public function reserve(int $seatCount): ReservationOption
    {
        $option = new ReservationOption($this->id, $seatCount);
        if ($this->countReservedSeats() + $seatCount > $this->getMaxReservableSeats()) {
            return $option;
        }

        foreach ($this->seats as $seat) {
            if (!$seat->isAvailable()) {
                continue;
            }
            $option->addSeat($seat->getSeat());
            if ($option->isFullfiled()) {
                break;
            }
        }

        return $option;
    }

    private function countAvailableSeats(): int
    {
        return array_reduce(
            $this->seats,
            static fn(int $carry, SeatWithBookReference $seat) => $carry + ($seat->isAvailable() ? 1 : 0),
            0,
        );
    }

    private function countReservedSeats(): int
    {
        return count($this->seats) - $this->countAvailableSeats();
    }

    private function getMaxReservableSeats(): int
    {
        return (int) floor(count($this->seats) * self::RESERVATION_MAX_THRESHOLD_PERCENT);
    }
}
