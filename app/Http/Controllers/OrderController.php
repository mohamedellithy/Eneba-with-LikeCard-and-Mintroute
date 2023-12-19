<?php

namespace App\Http\Controllers;

use App\Models\EnebaOrder;
use Illuminate\Http\Request;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = false);
    }

    public function index(){
        return view('pages.orders.index');
    }

    public function show(){
        return view('pages.orders.show');
    }

    public function eneba_callback_stock_provision(Request $request){
        set_time_limit(1000000);
        Http::post('https://webhook.site/719867a1-c405-453a-97f0-0968f7834d11',$request->all());
        return $this->eneba_service->eneba_callback_stock_provision();
    }

    public function eneba_callback_stock_reservation(Request $request){
        set_time_limit(1000000);
        Http::post('https://webhook.site/719867a1-c405-453a-97f0-0968f7834d11',$request->all());
        return $this->eneba_service->eneba_callback_stock_reservation();
    }

    public function eneba_orders(){
        $eneba_orders = EnebaOrder::paginate(10);
        return view('pages.orders.index',compact('eneba_orders'));
    }
}
