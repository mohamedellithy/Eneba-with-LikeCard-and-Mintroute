<?php
use App\Services\Eneba\Eneba;
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

if(!function_exists('GetAttachments')) {
    function GetAttachments($attachments_id)
    {
        $media_ids = explode(',', $attachments_id);
        $attachments = \App\Models\Image::whereIn('id', $media_ids)->get();
        return $attachments;
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
                    'amount' => $auction['node']['price']['amount'],
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

if(!function_exists('eneba_single_price')) {
    function eneba_single_price($eneba_id){
        $eneba_service      = new Eneba($sandbox = false);
        $product_eneba      = Cache::rememberForever('eneba_single_product_'.$eneba_id, function() use($eneba_id){
            return $this->eneba_service->get_single_product($eneba_id)['result']['data'];
        });
        return $product_eneba;
    }
}

