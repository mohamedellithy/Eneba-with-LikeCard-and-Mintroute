<?php
namespace App\Services\Eneba;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
use App\Models\EnebaOrder;
use App\Models\Auction;
use App\Models\EnebaOrderAuction;
use App\Models\OfflineCode;

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
            'status_order' => 'RESERVE',
        ]);

        foreach(request('auctions') as $auction):
            $auction = Auction::where('auction',$auction['auctionId'])->first();
            EnebaOrderAuction::updateOrCreate([
                'eneba_order_id'  => $eneba_order->id,
                'eneba_auction_id'=> $auction->id
            ]);
        endforeach;
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
            $data []= self::order_stock($auction,$eneba_order->count_cards);
        endforeach;

        return $data;
    }

    public static function order_stock($auction,$count_key_required){
        $auction_details['auctionId'] = $auction->auction;
        $offline_codes  = OfflineCode::query();
        $offline_codes->where([
            'product_id'   => $auction->product_id,
            'product_type' => 'eneba',
            'status'       => 'allow',
            'status_used'  => 'unused'
        ])->orWhere([
            'product_id'   => $auction->product->likecard_prod_id,
            'product_type' => 'likecard',
            'status'       => 'allow',
            'status_used'  => 'unused'
        ])->take($count_key_required);

        foreach($offline_codes->get() as $key_code):
            $auction_details['keys'][] = [
                "type"  => "TEXT",
                "value" => $key_code->code
            ];
        endforeach;

        // if(($rest_of_codes_required = $count_key_required - $offline_codes->count()) > 0):

        // endif;

        return $auction_details;
    }
}
