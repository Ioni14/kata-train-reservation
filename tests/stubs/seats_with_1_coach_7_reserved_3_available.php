<?php

use App\Domain\BookReference;
use App\Domain\Seat;
use App\Domain\SeatWithBookReference;

return [
    new SeatWithBookReference(new Seat('A', 1), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 2), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 3), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 4), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 5), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 6), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 7), new BookReference('abcdef1')),
    new SeatWithBookReference(new Seat('A', 8), new BookReference()),
    new SeatWithBookReference(new Seat('A', 9), new BookReference()),
    new SeatWithBookReference(new Seat('A', 10), new BookReference()),
];
