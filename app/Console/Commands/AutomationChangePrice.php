<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\AutomationWatchPrice;

class AutomationChangePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:automation_change_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $automation_change_price = new AutomationWatchPrice();
        //Http::get("https://webhook.site/eccb7698-ad7b-4231-a09b-f717526336d0","hi mohamd");
        return Command::SUCCESS;
    }
}
