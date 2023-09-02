<?php namespace App\Services;

use Illuminate\Support\Facades\Http;

class AutomationWatchPrice{
    public function __construct(){
       $this->getAuctionPrices();
    }

    public function getAuctionPrices(){
        $auctions = GetAuctionPrices("514ddadc-ca61-11ea-ab22-3a2292dc98c9");
        Http::post('https://webhook.site/eccb7698-ad7b-4231-a09b-f717526336d0',$auctions);
    }
}
