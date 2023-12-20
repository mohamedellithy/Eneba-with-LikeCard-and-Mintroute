<?php

namespace App\Jobs;

use App\Services\Eneba\Eneba;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RenewStockEneba implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $auction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($auction)
    {
        //
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        Http::withOptions([
            'verify' => false
        ])->post("https://webhook-test.com/7d3ac940487cde0ea4db84417f0986b2",[
            'test' => $this->auction
        ]);
        // $eneba = new Eneba($sandbox = false);
        // $eneba->update_create_auction($this->auction);
    }
}
