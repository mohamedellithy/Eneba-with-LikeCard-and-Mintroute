<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
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

    public function get_products(){
        $products = collect($this->likecard_service->get_products());
        dd($products);
        return view('pages.likecard.products.index',compact('products'));
    }
}
