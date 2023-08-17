<?php
use App\Services\Eneba\Eneba;

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


if(!function_exists('GetAuctionHighPrice')) {
    function GetAuctionHighPrice($eneba_id,$page_no = null)
    {
        $auction_eneba      = new Eneba($sandbox = false);
        $auction_eneba      = $auction_eneba->get_single_product($eneba_id,$page_no);

        if($auction_eneba['code'] == 200){
            $auction_eneba = $auction_eneba['result']['data'];
        
            $collect_auctions[] = $auction_eneba['auctions']['edges'];

            if($auction_eneba['auctions']['edges']['pageInfo']['hasNextPage'] == true){
                GetAuctionHighPrice($eneba_id,$auction_eneba['auctions']['edges']['pageInfo']['endCursor']);
            }

            return dd($collect_auctions);
        }

        return null;
    }
}


