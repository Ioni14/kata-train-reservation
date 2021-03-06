<?php

namespace Tests;

use App\Domain\BookReference;
use App\Domain\ReservationOption;
use App\Domain\Seat;
use App\Domain\SeatWithBookReference;
use App\Domain\Train;
use PHPUnit\Framework\TestCase;

class TrainTest extends TestCase
{
    /** @test */
    public function it_should_reserve_1_seat_for_3_empty_seats_train(): void
    {
        $trainId = 'express_2000';
        $train = new Train($trainId, require __DIR__.'/stubs/seats_with_1_coach_3_availables.php');
        $option = $train->reserve(1);

        $expectedOption = new ReservationOption($trainId, 1);
        $expectedOption->addSeat(new Seat('A', 1));
        static::assertEquals($expectedOption, $option);
    }

    /** @test */
    public function it_should_not_reserve_if_requested_seats_more_than_available_seats(): void
    {
        $trainId = 'express_2000';
        $train = new Train($trainId, require __DIR__ . '/stubs/seats_with_11_availables.php');
        $option = $train->reserve(11);

        static::assertEquals(new ReservationOption($trainId, 11), $option);
    }

    /** @test */
    public function it_should_not_reserve_if_more_than_70_percent_seats_overall(): void
    {
        $trainId = 'express_2000';
        $train = new Train($trainId, require __DIR__ . '/stubs/seats_with_1_coach_7_reserved_3_available.php');
        $option = $train->reserve(1);

        static::assertEquals(new ReservationOption($trainId, 1), $option);
    }

    /** @test */
    public function it_should_reserve_less_than_70_percent_seats_on_empty_train(): void
    {
        $trainId = 'express_2000';
        $train = new Train($trainId, require __DIR__ . '/stubs/seats_with_11_availables.php');
        $option = $train->reserve(7);

        $expectedOption = new ReservationOption($trainId, 7);
        $expectedOption->addSeat(new Seat('A', 1));
        $expectedOption->addSeat(new Seat('A', 2));
        $expectedOption->addSeat(new Seat('A', 3));
        $expectedOption->addSeat(new Seat('A', 4));
        $expectedOption->addSeat(new Seat('A', 5));
        $expectedOption->addSeat(new Seat('A', 6));
        $expectedOption->addSeat(new Seat('A', 7));
        static::assertEquals($expectedOption, $option);
    }

    /** @test */
    public function it_should_not_reserve_8_seats_on_a_11_seats_empty_train(): void
    {
        $trainId = 'express_2000';
        $train = new Train($trainId, require __DIR__ . '/stubs/seats_with_11_availables.php');
        $option = $train->reserve(8);

        $expectedOption = new ReservationOption($trainId, 8);
        static::assertEquals($expectedOption, $option);
    }
}
