<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;

class DemoPurchasingController extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = true);
    }
    public function index(Request $request){
        $request->merge([
            'orderId'  => '347c4978-4f81-11ed-bdc3-0242ac120002',
            'auctionId' => '6ce664fa-4abe-11ed-b878-0242ac120002'
        ]);
        Http::post('https://webhook.site/f032ba41-f451-4aba-a8b3-a97fbff114de',$request->all());
        //$this->eneba_service->eneba_callback_stock_provision();

        $this->eneba_service->eneba_callback_stock_reservation();
    }
}
