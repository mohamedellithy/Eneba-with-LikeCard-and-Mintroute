<?php
namespace App\Services\Eneba;
use App\Models\Auction;
use App\Models\EnebaOrder;
use App\Models\OfflineCode;
use App\Jobs\RenewStockEneba;
use App\Models\ProviderOrder;
use App\Models\EnebaOrderAuction;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

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

        $like_card_balancy   = new Likecard();
        $full_balance        = $like_card_balancy->check_balance();
        $offline_codes_count = 0;
        $key_count_required  = 0;
        $balance             = isset($full_balance['balance']) ? exchange_currency(round($full_balance['balance'],2)) : 0;
        $status              = true;
        foreach(request('auctions') as $auction):
            $auction_details      = Auction::where('auction',$auction['auctionId'])->first();
            $offline_codes_count  = OfflineCode::where([
                'product_id'   => $auction_details->product_id,
                'product_type' => 'eneba',
                'status'       => 'allow',
                'status_used'  => 'unused'
            ])->orWhere(function(Builder $query) use($auction_details){
                $query->where([
                    'product_id'   => $auction_details->product->likecard_prod_id,
                    'product_type' => 'likecard',
                    'status'       => 'allow',
                    'status_used'  => 'unused'
                ]);
            })->count();
            

            $likcard_product = $like_card_balancy->get_single_product($auction_details->product->likecard_prod_id);
            if($likcard_product['data'][0]['available'] != true){
                return null;
                break;
            }

            $key_count_required =  $auction['keyCount'];

            // for test only
            if($offline_codes_count < $key_count_required):
                $rest_count_needed = $key_count_required - $offline_codes_count;
                $balance           = $balance - ($rest_count_needed * ($auction['price']['amount'] / 100));
                if($balance < 0):
                    $status = false;
                    return null;
                    break;
                endif;
            endif;

            EnebaOrderAuction::updateOrCreate([
                'eneba_order_id'     => $eneba_order->id,
                'eneba_auction_id'   => $auction_details->id,
            ],[
                'key_count_required' => $auction['keyCount'],
                'unit_price'         => $auction['price']['amount'],
            ]);

        endforeach;

        return $status;
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
        $count_required = $auction->pivot->key_count_required ?: $auction->count_cards;
        $offline_codes  = OfflineCode::where([
            'product_id'   => $auction->product_id,
            'product_type' => 'eneba',
            'status'       => 'allow',
            'status_used'  => 'unused'
        ])->orWhere(function(Builder $query) use($auction){
            $query->where([
                'product_id'   => $auction->product->likecard_prod_id,
                'product_type' => 'likecard',
                'status'       => 'allow',
                'status_used'  => 'unused'
            ]);
        })->limit($count_required);

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
            $likecard_result = $LikeCard->create_bulk_likecard_order(
                $auction->product->likecard_prod_id,
                $rest_of_codes_required ?: 1,
                $auction->id
            );

            $likecard_order_id = $likecard_result['bulkOrderId'];

            if(isset($likecard_result['bulkOrderId'])):
                $likecard_result = $LikeCard->get_bulk_order(
                    $likecard_result['bulkOrderId']
                );

                ProviderOrder::updateOrCreate([
                    'order_auction_id'  => $auction->pivot->eneba_auction_id,
                    'provider_order_id' => $likecard_order_id,
                    'provider_name'     => 'LikeCard'
                ],[
                    'response'          => json_encode($likecard_result)
                ]);
            endif;

            if($likecard_result && ($likecard_result['response'] == 1) && (count($likecard_result['orders']) > 0) ):
                foreach($likecard_result['orders'] as $order):
                    if(count($order['serials']) > 0):
                        foreach($order['serials'] as $like_card_code):
                            $auction_details['keys'][] = [
                                "type"  => "TEXT",
                                "value" => $LikeCard->decryptSerial($like_card_code['serialCode']).'  Serial Number :'.$like_card_code['serialNumber']
                            ];

                            OfflineCode::create([
                                'product_id'   => $auction->product->likecard_prod_id,
                                'product_type' => 'likecard',
                                'status'       => 'allow',
                                'status_used'  => 'used',
                                'product_name' => $order['productName'],
                                'code'         => $LikeCard->decryptSerial($like_card_code['serialCode']).'  Serial Number :'.$like_card_code['serialNumber']
                            ]);
                        endforeach;
                    endif;
                endforeach;
            else:
                return null;
            endif;
        endif;

        if(!isset($auction_details['keys']) || (count($auction_details['keys']) == 0)):
            return null;
        endif;

        OfflineCode::whereIn('id',$used_codes)->update([
            'status_used'  => 'used'
        ]);

        if($auction->autoRenew == 1):
            RenewStockEneba::dispatch($auction);
        elseif($auction->autoRenew == 0):
            $auction->decrement('count_cards',$count_required ?: 1);
        endif;

        return $auction_details;
    }
}
