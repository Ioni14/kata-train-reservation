<?php

namespace App\Application;

use App\Domain\BookReference;
use App\Domain\Seat;
use App\Domain\Train;

interface TrainDataProviderInterface
{
    public function getTrain(string $trainId): Train;

    /**
     * @param Seat[] $seats
     */
    public function reserveSeats(string $trainId, BookReference $bookReference, array $seats): void;
}
