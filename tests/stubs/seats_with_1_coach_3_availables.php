<?php

use App\Domain\BookReference;
use App\Domain\Seat;
use App\Domain\SeatWithBookReference;

return [
    new SeatWithBookReference(new Seat('A', 1), new BookReference()),
    new SeatWithBookReference(new Seat('A', 2), new BookReference()),
    new SeatWithBookReference(new Seat('A', 3), new BookReference()),
];
