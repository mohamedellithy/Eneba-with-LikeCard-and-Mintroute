<?php
use App\Services\Eneba\Eneba;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

if(!function_exists('upload_assets')){
    function upload_assets($image){
        if($image):
            $value = isset($image->path) ? $image->path : 'default.jpg';
        else:
            $value = 'default.jpg';
        endif;

        return  asset('storage/'.$value);
    }
}


if(!function_exists('IsActiveOnlyIf')){
    function IsActiveOnlyIf($routes = []){
        if(count($routes) == 0) return '';

        $current_route = \Route::currentRouteName();

        if(in_array($current_route,$routes)):
            return 'active open';
        endif;

        return '';
    }
}


if(!function_exists('TrimLongText')){
    function TrimLongText($text,$length = 100){
        return substr($text,0,$length).' ... ';
    }
}

if(!function_exists('FormatePrice')) {
    function FormatePrice($price,$currency = true)
    {
        $total = ($price/100);
        if($currency == true):
            $total = $total.'€';
        endif;
        return $total;
    }
}


if(!function_exists('GetAuctionPrices')) {
    function GetAuctionPrices($eneba_id,$page_no = null)
    {
        global $collect_auctions;
        $auction_eneba      = new Eneba($sandbox = false);
        $auction_eneba      = $auction_eneba->get_single_product($eneba_id,$page_no);

        if($auction_eneba['code'] == 200){
            $auction_eneba = $auction_eneba['result']['data'];

            foreach($auction_eneba['S_product']['auctions']['edges'] as $auction):
                $data = [
                    'amount'       => $auction['node']['price']['amount'],
                    'belongsToYou' => $auction['node']['belongsToYou'],
                    'merchantName' => $auction['node']['merchantName']
                ];
                $collect_auctions[] = $data;
            endforeach;

            if($auction_eneba['S_product']['auctions']['pageInfo']['hasNextPage'] == true){
                GetAuctionPrices($eneba_id,$auction_eneba['S_product']['auctions']['pageInfo']['endCursor']);
            }

            $collect_prices = collect($collect_auctions);

            // if($type == 'high'){
            //     return $collect_prices->sortByDesc('amount')->first();
            // } else if($type == 'low'){
            //     return $collect_prices->sortBy('amount')->first();
            // }
            return $collect_prices;
        }

        return null;
    }
}

if(!function_exists('GetMyAuctions')) {
    function GetMyAuctions($product_id)
    {
        $eneba_service = new Eneba($sandbox = false);
        $result        = $eneba_service->get_single_product($product_id, null, true);
        if(isset($result['result']['data']['S_product']['auctions']['edges'])):
            if(count($result['result']['data']['S_product']['auctions']['edges']) > 0):
                return $result['result']['data']['S_product']['auctions']['edges'][0];
            endif;
        endif;
        return null;
    }
}

if(!function_exists('eneba_single_product')) {
    function eneba_single_product($eneba_id){
        $eneba_service      = new Eneba($sandbox = false);
        $product_eneba      = Cache::rememberForever('eneba_single_product_'.$eneba_id, function() use($eneba_id,$eneba_service){
            return $eneba_service->get_single_product($eneba_id)['result']['data'];
        });
        return $product_eneba;
    }
}


if(!function_exists('likecard_single_product')) {
    function likecard_single_product($likecard_id){
        $likecard_service = new LikeCard();
        $product_likecard =  Cache::rememberForever('likecard_product_'.$likecard_id, function() use($likecard_id,$likecard_service){
            return $likecard_service->get_single_product($likecard_id);
        });
        return $product_likecard;
    }
}

function get_settings($name,$application = 'general'){
    $setting_value = ApplicationSetting::where([
        'application'  => $application,
        'name'         => $name
    ])->value('value');

    return $setting_value ?: 0;
}


function exchange_currency($amount,$from = 'USD',$to = 'EUR'){
    // $response = Http::withOptions([
    //     'verify' => false
    // ])->withHeaders([
    //     'apikey' => 'hUbZSrc2OIHH818soPvCENH7hfn2JZ19'
    // ])->get("https://api.apilayer.com/fixer/convert?to={$to}&from={$from}&amount={$amount}");

    // if($response->successful()):
    //     $result = $response->json();
    //     return round($result['result'],2);
    // endif;

    return round($amount * get_settings('exchange_rate'),2);
}

