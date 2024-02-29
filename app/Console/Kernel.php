<?php

namespace App\Console;

use App\Models\LogAuctionPrice;
use Illuminate\Support\Facades\Http;
use App\Services\AutomationWatchPrice;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //Commands\AutomationChangePrice::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('command:automation_change_price')->everyMinute();

        // Http::post('https://webhook-test.com/069aa4df8eb8e8b3f9115a8f743f4b9c',[
        //     'hh' => 'hi'
        // ]);

        $automation_change_price = new AutomationWatchPrice($schedule);
        $automation_change_price->schedule;

        // $schedule->call(function(){
        //     LogAuctionPrice::truncate();
        // })->name("remove all price changes")->timezone("Africa/Cairo")->withoutOverlapping()->onOneServer()->between('00:00','00:30');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
