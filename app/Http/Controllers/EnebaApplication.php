<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\EnebaOrder;
use App\Models\OfflineCode;

class EnebaApplication extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = false);
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
        $this->eneba_service->eneba_callback_stock_provision();
    }

    public function eneba_callback_stock_reservation(Request $request){
        Http::post('https://webhook.site/5d007510-7555-48e9-82e5-0b20a60718cc',$request->all());
        $this->eneba_service->eneba_callback_stock_reservation();
    }

    public function get_products(Request $request){
        $page_no = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        $search  = request('search') ? $request->query('search') : null;
        $products  = Cache::rememberForever('eneba_products_'.$search.'_'.$page_no, function() use($page_no,$search){
            return $this->eneba_service->get_products($page_no,$search);
        });

        return view('pages.eneba.products.index',compact('products'));
    }

    public function add_eneba_codes(Request $request,$enebe_id){
        $product_eneba  = Cache::rememberForever('eneba_single_product_'.$enebe_id, function() use($enebe_id){
            return $this->eneba_service->get_single_product($enebe_id)['result']['data'];
        });

        $eneba_offline_codes = OfflineCode::where('product_id',$enebe_id)->get();

        return view('pages.eneba.codes.index',compact('product_eneba','eneba_offline_codes'));
    }

    public function store_eneba_codes(Request $request){
        $request->merge([
            'product_type' => 'eneba'
        ]);

        $eneba_id = request('product_id');

        $product_eneba  = Cache::rememberForever('eneba_single_product_'.$eneba_id, function() use($eneba_id){
            return $this->eneba_service->get_single_product($eneba_id)['result']['data'];
        });

        if($product_eneba['S_product']):
            $request->merge([
                'product_name'  => $product_eneba['S_product']['name'],
            ]);
        endif;

        $offline_code = OfflineCode::updateOrCreate($request->only([
            'product_id',
            'product_name',
            'product_image',
            'category_id',
            'product_type',
            'code'
        ]));

        return back();
    }

    public function update_eneba_codes(Request $request,$code_id){
        OfflineCode::where('id',$code_id)->update($request->only([
            'status_used',
            'status'
        ]));

        return back();
    }
}
