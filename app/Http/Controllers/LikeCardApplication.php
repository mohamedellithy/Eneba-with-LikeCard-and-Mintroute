<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Cache;
use App\Models\OfflineCode;
class LikeCardApplication extends Controller
{
    //
    protected $application;
    protected $likecard_service;

    public function __construct(){
        $this->application      = 'likecard';
        $this->likecard_service = new LikeCard();
    }

    public function update_credentials(Request $request,$section){
        $credintal = [
            'prod_email',
            'prod_deviceId',
            'prod_securityCode',
            'prod_password',
            'prod_phone',
            'prod_hash_key',
            'prod_secret_key',
            'prod_secret_iv'
        ];

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
        $eneba = (new LikeCard())->generate_token();
        ApplicationSetting::updateOrCreate([
            'application' => $this->application,
            'name'        => 'access_token'
        ],[
            'value'       => $eneba['access_token']
        ]);

        flash('Application is settings saved successfully','success');

        return back();
    }

    public function get_products(Request $request){
        $category      = null;
        if($request->has('category_id')):
            $category  = $request->query('category_id');
        endif;
        $categories = $this->likecard_service->get_categories();
        $products   = $this->likecard_service->get_products($category);
        if($categories['response'] == 1):
            $categories = $categories['data'];
        else:
            $categories = [];
        endif;

        if($products['response'] == 1):
            $products = $products['data'];
        else:
            $products = [];
        endif;

        return view('pages.likecard.products.index',compact('products','categories'));
    }

    public function get_codes(Request $request){
        $category      = null;
        if($request->has('category_id')):
            $category  = $request->query('category_id');
        endif;
        $categories = Cache::rememberForever('likecard_categories', function(){
            $categories = $this->likecard_service->get_categories();
            return isset($categories['data']) ? $categories['data'] : [];
        });

        $products = Cache::rememberForever('likecard_products_'.$category, function() use($category){
            $products   = $this->likecard_service->get_products($category);
            if(isset($products['data'])):
                if($products['response'] == 1):
                    $products = isset($products['data']) ? $products['data']: [];
                endif;
            endif;

            return $products;
        });

        return view('pages.likecard.codes.index',compact('products','categories'));
    }

    public function store_codes(Request $request){

        $request->merge([
            'product_type' => 'eneba'
        ]);

        $offline_code = OfflineCode::updateOrCreate($request->only([
            'product_id',
            'category_id',
            'product_type',
            'code'
        ]));

        return back();
    }
}
