<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Eneba\Eneba;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;

class DemoPurchasingController extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = false);
    }
    public function index(Request $request){
        // $request->merge([
        //     'orderId'  => '347c4978-4f81-11ed-bdc3-0242ac120002'
        // ]);

        //$this->eneba_service->eneba_callback_stock_provision();
        // 7d0462a2-fdec-11ec-9ceb-b62153817ae7
        // 
        $result = GetMyAuctions("9fea8e4c-c54a-11e8-a803-186590d66063");
        dd($result);

        //dd($this->eneba_service->register_stock_reservation());
        //dd($this->eneba_service->register_stock_provision());
       //- dd($this->eneba_service->get_callbacks_registered());
        // dd($this->eneba_service->sandbox_trigger_stock_reservation());
        //dd($this->eneba_service->sandbox_trigger_stock_provision());
       //dd($this->eneba_service->credentail);
        // $likecard = new LikeCard();
        // $response = $likecard->create_bulk_likecard_order(376,5);
        // dd($response);
        // dd($this->eneba_service->eneba_callback_stock_provision());
    }
}
