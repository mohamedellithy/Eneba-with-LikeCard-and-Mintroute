<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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
        Http::post('https://webhook.site/4e302f27-0ad9-43ea-8573-0969708fb17e',$request->all());
        // return response()->json([
        //     "action"  => "RESERVE",
        //     "orderId" => $request->input('orderId'),
        //     "success" => true
        // ],200);
    }

    public function eneba_callback_stock_reservation(Request $request){
        Http::post('https://webhook.site/4e302f27-0ad9-43ea-8573-0969708fb17e',$request->all());
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
