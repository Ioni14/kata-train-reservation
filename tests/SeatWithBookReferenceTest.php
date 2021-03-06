<?php

namespace Tests;

use App\Domain\BookReference;
use App\Domain\Seat;
use App\Domain\SeatWithBookReference;
use PHPUnit\Framework\TestCase;

class SeatWithBookReferenceTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideNotAvailableSeats
     */
    public function it_should_not_be_available(BookReference $bookReference): void
    {
        $seat = new SeatWithBookReference(new Seat('A', 1), $bookReference);
        static::assertFalse($seat->isAvailable());
    }

    public function provideNotAvailableSeats(): iterable
    {
        yield [new BookReference('abcdef')];
        yield [new BookReference('')];
    }

    /**
     * @test
     */
    public function it_should_be_available(): void
    {
        $seat = new SeatWithBookReference(new Seat('A', 1), new BookReference());
        static::assertTrue($seat->isAvailable());
    }
}
