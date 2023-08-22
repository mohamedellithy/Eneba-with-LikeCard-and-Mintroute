<?php

namespace App\Http\Controllers;

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
        Http::post('https://webhook.site/5d007510-7555-48e9-82e5-0b20a60718cc',$request->all());
        $this->eneba_service->eneba_callback_stock_provision();
    }

    public function eneba_callback_stock_reservation(Request $request){
        Http::post('https://webhook.site/5d007510-7555-48e9-82e5-0b20a60718cc',$request->all());
        $this->eneba_service->eneba_callback_stock_reservation();
    }
}
