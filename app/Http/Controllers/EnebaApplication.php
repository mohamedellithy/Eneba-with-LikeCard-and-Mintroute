<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\EnebaOrder;
class EnebaApplication extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba();
    }

    public function update_credentials(Request $request,$section){

        if($section == 'sandbox'):
            $credintal = [
                'sandbox_auth_id',
                'sandbox_auth_secret'
            ];
        elseif($section == 'prod'):
            $credintal = [
                'prod_auth_id',
                'prod_auth_secret'
            ];
        endif;

        foreach($request->only($credintal) as $name => $value):
            $main = [
                'application' => $this->application,
                'name'        => $name
            ];

            $data = [
                'value'       => $value
            ];

            ApplicationSetting::updateOrCreate($main,$data);
        endforeach;


        flash('Application is settings saved successfully','success');
        return back();
    }

    public function generate_token(){
        $eneba = $this->eneba_service->generate_token();
        ApplicationSetting::updateOrCreate([
            'application' => $this->application,
            'name'        => 'access_token'
        ],[
            'value'       => $eneba['access_token']
        ]);

        flash('Application is settings saved successfully','success');

        return back();
    }

    public function eneba_callback_stock_provision(Request $request){
        Http::post('https://webhook.site/5d007510-7555-48e9-82e5-0b20a60718cc',$request->all());
        $order_eneba = EnebaOrder::where('order_id',$request->input('orderId'))->first();
        $order_eneba->update([
            'status_order' => 'PROVIDE',
        ]);
        return response()->json([
            "action"  => "PROVIDE",
            "orderId" => $request->input('orderId'),
            "success" => true,
            "auctions" => [
                [
                    "auctionId" => $order_eneba->auctions,
                    "keys" => [
                        [
                            "type"  => "TEXT",
                            "value" => "QS8ND-G0W76-BTSQO-WAAJA-6LCD3"
                        ]
                    ]
                ]
            ]
        ],200);
    }

    public function eneba_callback_stock_reservation(Request $request){
        Http::post('https://webhook.site/5d007510-7555-48e9-82e5-0b20a60718cc',$request->all());
        EnebaOrder::where([
            'auctions'     => $request->input('auctions')[0]['auctionId'],
            'product_id'   => 123
        ])->update([
            'order_id'     => $request->input('orderId'),
            'status_order' => 'RESERVE',
        ]);
        return response()->json([
            "action"  => "RESERVE",
            "orderId" => $request->input('orderId'),
            "success" => true
        ],200);
    }

    public function get_products(Request $request){
        $page_no = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        $search  = request('search') ? $request->query('search') : null;
        $products  = Cache::rememberForever('eneba_products_'.$search.'_'.$page_no, function() use($page_no,$search){
            return $this->eneba_service->get_products($page_no,$search);
        });

        return view('pages.eneba.products.index',compact('products'));
    }
}
