<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class AuctionApplication extends Controller
{
    //

    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = false);
    }

    public function index(Request $request){
        $auctions  = Auction::paginate(20);
        $search    = request('name');
        $products  = null;
        if($search = request('name')){
            $products  = Cache::rememberForever('eneba_products_'.$search, function() use($search){
                return $this->eneba_service->get_products($page_no = null,$search);
            });
        }
        //dd($products);
        return view('pages.auctions.index',compact('auctions','products'));
    }

    // public function ajax_search_on_eneba_products(Request $request){
    //     $search    = request('eneba_product_name');
    //     $products  = Cache::rememberForever('eneba_products_'.$search, function() use($search){
    //         return $this->eneba_service->get_products($page_no = null,$search);
    //     });
    //     return response()->json([
    //         'products' => $products['result']
    //     ]);
    // }
}
