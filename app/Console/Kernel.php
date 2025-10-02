<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $bookings = Booking::with('istrument')
                ->where('status', 'approved')
                ->where("CONCAT(date, ' ' , end_time) <= ?", [now()])
                ->get();
            
            foreach ($bookings as $booking) {
                foreach ($booking->instruments as $item) {
                    $item->increment('stock', $item->pivot->quantity);
                }
                $booking->update(['status' => 'finished']);
            }
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
