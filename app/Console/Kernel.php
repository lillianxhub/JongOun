<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $bookings = Booking::with('instruments')
                ->where('status', 'approved')
                ->whereRaw("CONCAT(date, ' ' , end_time) <= ?", [now()])
                ->get();
            
            DB::transaction(function () use ($bookings) {
                foreach ($bookings as $booking) {
                    foreach ($booking->instruments as $instrument) {
                        // Increment the stock of each instrument
                        $instrument->increment('stock', $instrument->pivot->quantity);
                    }
                    // Update the booking status to 'finished'
                    $booking->update(['status' => 'finished']);
                }
            });
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
