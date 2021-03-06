<?php

namespace App\Domain;

use Webmozart\Assert\Assert;

class Reservation
{
    /**
     * @param Seat[] $seats
     */
    public function __construct(
        public string $trainId,
        public array $seats,
        public BookReference $bookingId,
    ) {
        /** @var iterable $seats */
        Assert::allIsInstanceOf($seats, Seat::class);
    }
}
