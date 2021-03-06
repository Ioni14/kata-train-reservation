<?php

namespace Tests;

use App\Application\BookingReferencesProviderInterface;
use App\Application\TicketOfficeService;
use App\Application\TrainDataProviderInterface;
use App\Domain\BookReference;
use App\Domain\Reservation;
use App\Domain\ReservationRequest;
use App\Domain\Seat;
use App\Domain\Train;
use PHPUnit\Framework\TestCase;

class TicketOfficeTest extends TestCase
{
    /** @test */
    public function it_should_reserve_seats_on_empty_train(): void
    {
        $expectedBookingId = '75bcd15';
        $bookingReferencesProvider = $this->createMock(BookingReferencesProviderInterface::class);
        $bookingReferencesProvider->method('getBookingReference')->willReturn(new BookReference($expectedBookingId));

        $trainId = 'express_2000';
        $trainDataProvider = $this->createMock(TrainDataProviderInterface::class);
        $trainDataProvider->method('getTrain')->willReturn(new Train($trainId, require __DIR__ . '/stubs/seats_with_1_coach_3_availables.php'));

        $service = new TicketOfficeService($bookingReferencesProvider, $trainDataProvider);
        $request = new ReservationRequest(trainId: $trainId, seatCount: 2);
        $reservation = $service->makeReservation($request);

        static::assertEquals(
            new Reservation(
                $trainId,
                [
                    new Seat('A', 1),
                    new Seat('A', 2),
                ],
                new BookReference($expectedBookingId),
            ),
            $reservation,
        );
    }

    /** @test */
    public function it_should_reserve_seats_that_not_already_reserved(): void
    {
        $expectedBookingId = '75bcd15';
        $bookingReferencesProvider = $this->createMock(BookingReferencesProviderInterface::class);
        $bookingReferencesProvider->method('getBookingReference')->willReturn(new BookReference($expectedBookingId));

        $trainId = 'express_2000';
        $trainDataProvider = $this->createMock(TrainDataProviderInterface::class);
        $trainDataProvider->method('getTrain')->willReturn(new Train($trainId, require __DIR__ . '/stubs/seats_with_1_coach_2_reserved_3_availables.php'));
        $trainDataProvider->expects(self::atLeastOnce())->method('reserveSeats')->with(
            $trainId,
            new BookReference($expectedBookingId),
            [new Seat('A', 2)],
        );

        $service = new TicketOfficeService($bookingReferencesProvider, $trainDataProvider);
        $request = new ReservationRequest(trainId: $trainId, seatCount: 1);
        $reservation = $service->makeReservation($request);

        static::assertEquals(
            new Reservation(
                $trainId,
                [
                    new Seat('A', 2),
                ],
                new BookReference($expectedBookingId),
            ),
            $reservation,
        );
    }

    /** @test */
    public function it_should_not_reserve_more_than_70_percent_seats_for_overall_train(): void
    {
        $expectedBookingId = '75bcd15';
        $bookingReferencesProvider = $this->createMock(BookingReferencesProviderInterface::class);
        $bookingReferencesProvider->method('getBookingReference')->willReturn(new BookReference($expectedBookingId));

        $trainId = 'express_2000';
        $trainDataProvider = $this->createMock(TrainDataProviderInterface::class);
        $trainDataProvider->method('getTrain')->willReturn(new Train($trainId, require __DIR__ . '/stubs/seats_with_11_availables.php'));

        $service = new TicketOfficeService($bookingReferencesProvider, $trainDataProvider);
        $request = new ReservationRequest(trainId: $trainId, seatCount: 8);
        $reservation = $service->makeReservation($request);

        static::assertEquals(
            new Reservation(
                $trainId,
                [],
                new BookReference(),
            ),
            $reservation,
        );
    }
}
