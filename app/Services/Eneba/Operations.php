<?php
namespace App\Services\Eneba;
use App\Models\Auction;
use App\Models\EnebaOrder;
use App\Models\OfflineCode;
use App\Models\EnebaOrderAuction;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;

class Operations {
    public static function create_new_auction($attr = []){
        Auction::where('product_id',$attr['product_id'])->update([
            'auction'    => $attr['auction']
        ]);
    }

    public static function create_new_order(){
        $eneba_order = EnebaOrder::updateOrCreate([
            'order_id'     => request('orderId')
        ],[
            'status_order' => 'RESERVE'
        ]);

        foreach(request('auctions') as $auction):
            $auction_details = Auction::where('auction',$auction['auctionId'])->first();
            EnebaOrderAuction::updateOrCreate([
                'eneba_order_id'     => $eneba_order->id,
                'eneba_auction_id'   => $auction_details->id,
            ],[
                'key_count_required' => $auction['keyCount'],
                'unit_price'         => $auction['price']['amount'],
            ]);
        endforeach;

        if($eneba_order):
            return true;
        endif;

        return null;
    }

    public static function update_orders_and_get_codes(){
        $eneba_order = EnebaOrder::where([
            'order_id'     => request('orderId')
        ])->first();

        $eneba_order->update([
            'status_order' => 'PROVIDE',
        ]);

        $data = [];


        foreach($eneba_order->auctions as $auction):
            $result = self::order_stock($auction);
            if($result == null):
                $data =  null;
                break;
            endif;

            $data[] = $result;
        endforeach;

        return $data;
    }

    public static function order_stock($auction){
        $auction_details['auctionId'] = $auction->auction;
        $used_codes     = [];
        $offline_codes  = OfflineCode::query();
        $count_required = $auction->pivot->key_count_required ?: $auction->count_cards;
        $offline_codes  = $offline_codes->where([
            'product_id'   => $auction->product_id,
            'product_type' => 'eneba',
            'status'       => 'allow',
            'status_used'  => 'unused'
        ])->orWhere([
            'product_id'   => $auction->product->likecard_prod_id,
            'product_type' => 'likecard',
            'status'       => 'allow',
            'status_used'  => 'unused'
        ])->take($count_required)->get();

        dd($offline_codes);

        foreach($offline_codes->get() as $key_code):
            $used_codes[]              = $key_code->id;
            $auction_details['keys'][] = [
                "type"  => "TEXT",
                "value" => $key_code->code
            ];
        endforeach;

        $rest_of_codes_required = $count_required - $offline_codes->count();
        if($rest_of_codes_required > 0):
            $LikeCard = new LikeCard();
            $likecard_result = $LikeCard->create_likecard_order(
                $auction->product->likecard_prod_id,
                $rest_of_codes_required ?: 1,
                $auction->id
            );

            if($likecard_result && ($likecard_result['response'] == 1) && (count($likecard_result['serials']) > 0) ):
                foreach($likecard_result['serials'] as $like_card_code):
                    $auction_details['keys'][] = [
                        "type"  => "TEXT",
                        "value" => $like_card_code['serialCode']
                    ];

                    OfflineCode::create([
                        'product_id'   => $auction->product->likecard_prod_id,
                        'product_type' => 'likecard',
                        'status'       => 'allow',
                        'status_used'  => 'used',
                        'product_name' => $likecard_result['productName'],
                        'product_image'=> $likecard_result['productImage'],
                        'code'         => $like_card_code['serialCode']
                    ]);
                endforeach;
            else:
                return null;
            endif;
        endif;

        if(!isset($auction_details['keys']) || (count($auction_details['keys']) == 0)):
            return null;
        endif;

        dd($used_codes);

        OfflineCode::whereIn('id',$used_codes)->update([
            'status_used'  => 'used'
        ]);
       
        return $auction_details;
    }
}
