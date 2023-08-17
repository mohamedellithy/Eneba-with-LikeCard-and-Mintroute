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

        $page_no   = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        $search    = request('name');
        $products  = null;
        if($search = request('name')){
            $auctions  = Auction::pluck('product_id')->toArray();
            $products  = Cache::rememberForever('eneba_products_'.$search.'_'.$page_no, function() use($page_no,$search){
                return $this->eneba_service->get_products($page_no,$search);
            });
        } else {
            $auctions  = Auction::paginate(20);
        }
        //dd($products);
        return view('pages.auctions.index',compact('auctions','products'));
    }

    public function create(Request $request,$eneba_id){
        // $product_eneba  = Cache::rememberForever('eneba_single_product_'.$eneba_id, function() use($eneba_id){
        //     return $this->eneba_service->get_single_product($eneba_id)['result']['data'];
        // });
        $page_no = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        //$product_eneba =  $this->eneba_service->get_single_product($eneba_id,$page_no)['result']['data'];

        $prices = GetAuctionPrices($eneba_id);

        dd($prices->sortBy('amount')->first(),$prices->sortByDesc('amount')->first());

        return view('pages.auctions.create',compact('product_eneba'));
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
