<?php

namespace Tests;

use App\Application\BookingReferencesProviderInterface;
use App\Application\TicketOfficeService;
use App\Application\TrainDataProvider;
use App\Application\TrainDataProviderInterface;
use App\Domain\BookReference;
use App\Domain\Reservation;
use App\Domain\ReservationRequest;
use App\Domain\Seat;
use App\Domain\Train;
use PHPUnit\Framework\TestCase;

class TicketOfficeTest extends TestCase
{
    /*
     * For a train overall, no more than 70% of seats may be reserved in advance
     * ideally no individual coach should have no more than 70% reserved seats either
     * you must put all the seats for one reservation in the same coach
     *
     * The Ticket Office service needs to respond to a HTTP POST request with form data telling you which train the customer wants to reserve seats on and how many they want
     * It should return a json document detailing the reservation that has been made :
     *      A reservation comprises a json document with three fields, the train id, booking reference, and the ids of the seats that have been reserved.
     *      example: {"train_id": "express_2000", "booking_reference": "75bcd15", "seats": ["1A", "1B"]}
     * If it is not possible to find suitable seats to reserve, the service should instead return an empty list of seats and an empty string for the booking reference
     *
     * you could write a command line program which takes the train id and number of seats as command line arguments, and returns the same json as above
     *
     * Booking Reference Service :
     *      * returns an unique booking reference
     * Train Data Service :
     *      * returns informations about the seats that a train has
     *      * to reserve seats on a train
     */

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
        $trainDataProvider->method('getSeats')->willReturn(require __DIR__ . '/stubs/seats_with_11_availables.php');

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
