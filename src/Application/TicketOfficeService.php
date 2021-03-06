<?php

namespace App\Application;

use App\Domain\BookReference;
use App\Domain\Reservation;
use App\Domain\ReservationRequest;
use App\Domain\Seat;

class TicketOfficeService
{
    public function __construct(
        private BookingReferencesProviderInterface $bookingReferencesProvider,
        private TrainDataProviderInterface $trainDataProvider,
    ) {
    }

    public function makeReservation(ReservationRequest $request): Reservation
    {
        $train = $this->trainDataProvider->getTrain($request->trainId);
        $reservationOption = $train->reserve($request->seatCount);

        if (!$reservationOption->isFullfiled()) {
            return new Reservation($request->trainId, [], new BookReference());
        }

        $bookingReference = $this->bookingReferencesProvider->getBookingReference();

        $this->trainDataProvider->reserveSeats($request->trainId, $bookingReference, $reservationOption->getSeats());

        return new Reservation($request->trainId, $reservationOption->getSeats(), $bookingReference);
    }
}
