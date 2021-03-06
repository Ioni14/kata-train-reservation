<?php

namespace Tests;

use App\Domain\ReservationOption;
use App\Domain\Seat;
use PHPUnit\Framework\TestCase;

class ReservationOptionTest extends TestCase
{
    /** @test */
    public function it_should_not_be_fullfiled_with_no_seats_and_1_requested_seat(): void
    {
        $option = new ReservationOption('express_2000', 1);
        static::assertFalse($option->isFullfiled());
    }

    /** @test */
    public function it_should_be_fullfiled_with_1_seat_and_1_requested_seat(): void
    {
        $option = new ReservationOption('express_2000', 1);
        $option->addSeat(new Seat('A', 1));
        static::assertTrue($option->isFullfiled());
    }
}
