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
        $this->schedule = $schedule;
        $this->eneba_service = new Eneba($sandbox = false);
        $auctions = Auction::has('logs_auction_price','<',10)->where([
            'status' => 1,
            'automation' => 1
        ])->get();

        Http::post('https://webhook-test.com/91db77804eebee9be1ef789221f8802e',[
            $auctions
        ]);

        foreach($auctions as $auction):
            $this->auction_settings  = $auction;
            $command = $this->schedule->call(function() use($auction){
                 $this->getAuctionPrices($auction);
            });

            $command->name("Auction no #".$auction->id);

            $command->timezone("Africa/Cairo");

            // $command->withoutOverlapping()->onOneServer();

            //$command->cron('*/'.$this->auction_settings->change_time.' * * * *');
        endforeach;
    }

    public function getAuctionPrices($auction){
        $auctions         = GetAuctionPrices($auction->product_id);
        $this->my_price   = $auctions->where('belongsToYou',true)->value('amount');
        $min_price        = $auctions->where('belongsToYou',false)->min('amount');
        $befor_last_price = $auctions->firstWhere('amount','>',$auction->min_price * 100);
        $befor_last_price = isset($befor_last_price) ? $befor_last_price['amount'] : null;
        $max_price        = $auctions->where('belongsToYou',false)->max('amount');
        $current_price    = $this->my_price;
        $section          = null;

        if($auctions->where('belongsToYou',false)->count() == 0):
            $current_price = $auction->max_price * 100;
        else:
            // in case of my_price  is greater than max_price and my_price is greater than min_price settings
            if(($this->my_price > $min_price) && ( $min_price > ($auction->min_price * 100) )):
                $current_price = $min_price - ($auction->price_step * 100);

                if($current_price < ($auction->min_price * 100)):
                    $current_price = $auction->min_price * 100;
                endif;
                //$section = "itme 1";

             // in case of my_price is equal max_price and my_price is less than or equal min_price settings
            elseif(($this->my_price >= $min_price) && ( $min_price <= ($auction->min_price * 100) )):
                if($befor_last_price):
                    $current_price = $befor_last_price - ($auction->price_step * 100);
                    if(($befor_last_price - $min_price) == 1):
                        $current_price = ($auction->min_price * 100);
                    endif;
                endif;

                if($current_price < ($auction->min_price * 100)):
                    $current_price = $auction->min_price * 100;
                endif;
                //$section = "itme 2";

            // in case of my_price is equal max_price and my_price is less than or equal min_price settings
            elseif(($this->my_price < $min_price) && ( $this->my_price >= ($auction->min_price * 100) )):
                $diff = $min_price - $this->my_price;
                if($diff > ($auction->price_step * 100)):
                    $current_price = $min_price - ($auction->price_step * 100);
                    //$section = "itme 3";
                endif;

                if($current_price < ($auction->min_price * 100)):
                    $current_price = $auction->min_price * 100;
                endif;
            endif;
        endif;

        if($this->my_price !=  $current_price):
            $this->update_price_on_auction($auction,$current_price);
        endif;
        Http::post('https://webhook-test.com/91db77804eebee9be1ef789221f8802e',[
            $current_price,
            $min_price,
            $befor_last_price,
            $section,
            $auction
        ]);
    }

    public function update_price_on_auction($auction,$current_price){
        $form   = $auction->current_price;
        $current_price = $current_price / 100;
        $auction->update([
            'current_price'  => $current_price
        ]);

        $response = $this->eneba_service->update_create_auction($auction);

        if(!isset($response['result']['errors'])):
            $auction->logs_auction_price()->create([
                'from'           => $form,
                'to'             => $auction->current_price,
                'eneba_response' => json_encode($response['result']),
                'status'         => $response['code']
            ]);
        endif;
    }
}
