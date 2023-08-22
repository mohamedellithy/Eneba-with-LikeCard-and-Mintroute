<?php
namespace App\Services\Eneba;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
use App\Models\EnebaOrder;
use App\Models\Auction;
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

        $auction = Auction::where('auction',request('auctionId'))->first();
        $eneba_order->auctions()->attach([
            'eneba_order_id'  => $eneba_order->id,
            'eneba_auction_id'=> $auction->id
        ]);
    }

    public static function update_orders_and_get_codes(){
        $eneba_order = EnebaOrder::where([
            'order_id'     => request('orderId'),
            'status_order' => 'RESERVE',
        ])->first();

        $eneba_order = $eneba_order->update([
            'status_order' => 'PROVIDE',
        ]);

        $data = [];

        foreach($eneba_order->auctions as $auction):
            $data []= [
                "auctionId" => $auction->auction,
                "keys" => [
                    [
                        "type"  => "TEXT",
                        "value" => "QS8ND-G0W76-BTSQO-WAAJA-6LCD3"
                    ]
                ]
            ];
        endforeach;

        return $data;
    }
}