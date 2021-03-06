<?php

namespace App\Application;

use App\Domain\BookReference;

interface BookingReferencesProviderInterface
{
    public function getBookingReference(): BookReference;
}
