<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class TestFinishBooking extends Command
{
    protected $signature = 'booking:finish-test';
    protected $description = 'Test finishing approved bookings';

    public function handle()
    {
        $now = now();

        $bookings = Booking::with('instruments')
            ->where('status', 'approved')
            ->whereRaw("CONCAT(date, ' ', end_time) <= ?", [$now])
            ->get();

        DB::transaction(function () use ($bookings) {
            foreach ($bookings as $booking) {
                foreach ($booking->instruments as $item) {
                    $item->increment('stock', $item->pivot->quantity);
                }
                $booking->update(['status' => 'finished']);
                $this->info("Booking ID {$booking->id} finished!");
            }
        });

        $this->info("Done testing finish booking.");
    }
}