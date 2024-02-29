<?php namespace App\Services;

use App\Models\Auction;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Scheduling\Schedule;
class AutomationWatchPrice{
    protected $my_price = 0;
    protected $auction_settings;
    protected $eneba_service;
    public    $schedule;
    public function __construct(Schedule $schedule){
        $this->eneba_service = new Eneba($sandbox = false);
        $auctions = Auction::has('logs_auction_price','<',10)->where([
            'status' => 1,
            'automation' => 1
        ])->get();

        Http::post('https://webhook-test.com/069aa4df8eb8e8b3f9115a8f743f4b9c',[
            'hh' => $auctions
        ]);

        foreach($auctions as $auction):
            $this->schedule = $schedule;
            $this->auction_settings  = $auction;

            Http::post('https://webhook-test.com/069aa4df8eb8e8b3f9115a8f743f4b9c',[
                'hh' => $auction
            ]);

            $command = $this->schedule->call(function(){
                 $this->getAuctionPrices();
            });

            $command->name("Auction no #".$this->auction_settings->id);

            $command->timezone("Africa/Cairo");

            //$command->withoutOverlapping()->onOneServer();

            // $command->cron('*/'.$this->auction_settings->change_time.' * * * *');
        endforeach;
    }

    public function getAuctionPrices(){
        $auctions         = GetAuctionPrices($this->auction_settings->product_id);
        $this->my_price   = $auctions->where('belongsToYou',true)->value('amount');
        $min_price        = $auctions->where('belongsToYou',false)->min('amount');
        $befor_last_price = $auctions->firstWhere('amount','>',$this->auction_settings->min_price * 100);
        $befor_last_price = isset($befor_last_price) ? $befor_last_price['amount'] : null;
        $max_price        = $auctions->where('belongsToYou',false)->max('amount');
        $current_price    = $this->my_price;
        $section          = null;

        if($auctions->where('belongsToYou',false)->count() == 0):
            $current_price = $this->auction_settings->max_price * 100;
        else:
            // in case of my_price  is greater than max_price and my_price is greater than min_price settings
            if(($this->my_price > $min_price) && ( $min_price > ($this->auction_settings->min_price * 100) )):
                $current_price = $min_price - ($this->auction_settings->price_step * 100);

                if($current_price < ($this->auction_settings->min_price * 100)):
                    $current_price = $this->auction_settings->min_price * 100;
                endif;
                //$section = "itme 1";

             // in case of my_price is equal max_price and my_price is less than or equal min_price settings
            elseif(($this->my_price >= $min_price) && ( $min_price <= ($this->auction_settings->min_price * 100) )):
                if($befor_last_price):
                    $current_price = $befor_last_price - ($this->auction_settings->price_step * 100);
                    if(($befor_last_price - $min_price) == 1):
                        $current_price = ($this->auction_settings->min_price * 100);
                    endif;
                endif;

                if($current_price < ($this->auction_settings->min_price * 100)):
                    $current_price = $this->auction_settings->min_price * 100;
                endif;
                //$section = "itme 2";

            // in case of my_price is equal max_price and my_price is less than or equal min_price settings
            elseif(($this->my_price < $min_price) && ( $this->my_price >= ($this->auction_settings->min_price * 100) )):
                $diff = $min_price - $this->my_price;
                if($diff > ($this->auction_settings->price_step * 100)):
                    $current_price = $min_price - ($this->auction_settings->price_step * 100);
                    //$section = "itme 3";
                endif;

                if($current_price < ($this->auction_settings->min_price * 100)):
                    $current_price = $this->auction_settings->min_price * 100;
                endif;
            endif;
        endif;

        if($this->my_price !=  $current_price):
            $this->update_price_on_auction($current_price);
        endif;
        Http::post('https://webhook-test.com/069aa4df8eb8e8b3f9115a8f743f4b9c',[
            $current_price,
            $min_price,
            $befor_last_price,
            $section,
            $auctions
        ]);
    }

    public function update_price_on_auction($current_price){
        $form   = $this->auction_settings->current_price;
        $current_price = $current_price / 100;
        $this->auction_settings->update([
            'current_price'  => $current_price
        ]);

        $response = $this->eneba_service->update_create_auction($this->auction_settings);

        if(!isset($response['result']['errors'])):
            $this->auction_settings->logs_auction_price()->create([
                'from'           => $form,
                'to'             => $this->auction_settings->current_price,
                'eneba_response' => json_encode($response['result']),
                'status'         => $response['code']
            ]);
        endif;
    }
}
