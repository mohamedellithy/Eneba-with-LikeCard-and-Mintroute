<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\Eneba\Eneba;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AuctionApplication extends Controller
{
    //

    protected $application;
    protected $eneba_service;
    protected $likecard_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba($sandbox = false);
        $this->likecard_service = new LikeCard();
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
            $auctions  = Auction::with('product')->paginate(20);
        }
        //dd($products);
        return view('pages.auctions.index',compact('auctions','products'));
    }

    public function create(Request $request,$eneba_id){
        $page_no = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        $product_eneba           =  $this->eneba_service->get_single_product($eneba_id,$page_no)['result']['data'];
        $eneba_likecard_relation = Product::where('eneba_prod_id',$eneba_id)->first();
        $likecard_product        = [];
        if($eneba_likecard_relation):
            $likecard_product        = $this->likecard_service->get_single_product($eneba_likecard_relation->likecard_prod_id);
            $likecard_product        = $likecard_product['data'];
        endif;
        return view('pages.auctions.create',compact('product_eneba','likecard_product'));
    }

    public function edit(Request $request,$auction_id){
        $page_no = request('prev')  ? $request->query('prev') : ($request->has('next') ? $request->query('next') : null);
        $auction                 = Auction::where('id',$auction_id)->first();
        $product_eneba           =  $this->eneba_service->get_single_product($auction->product_id,$page_no)['result']['data'];
        $eneba_likecard_relation = Product::where('eneba_prod_id',$auction->product_id)->first();
        $likecard_product        = [];
        if($eneba_likecard_relation):
            $likecard_product        = $this->likecard_service->get_single_product($eneba_likecard_relation->likecard_prod_id);
            $likecard_product        = $likecard_product['data'];
        endif;
        return view('pages.auctions.edit',compact('auction','product_eneba','likecard_product'));
    }

    public function store(Request $request,$eneba_id){
        $auction = Auction::create([
            'product_id'     => $eneba_id,
            'status'         => $request->input('status'),
            'count_cards'    => $request->input('count_cards'),
            'min_price'      => $request->input('min_price'),
            'max_price'      => $request->input('max_price'),
            'current_price'  => $request->input('current_price'),
            'automation'     => $request->input('automation'),
            'change_time'    => $request->input('change_time'),
            'price_step'     => $request->input('price_step')
        ]);

        return redirect()->route('application.auctions');
    }

    public function update(Request $request,$auction_id){
        $auction = Auction::where('id',$auction_id)->update([
            'status'         => $request->input('status'),
            'min_price'      => $request->input('min_price'),
            'count_cards'    => $request->input('count_cards'),
            'max_price'      => $request->input('max_price'),
            'current_price'  => $request->input('current_price'),
            'automation'     => $request->input('automation'),
            'change_time'    => $request->input('change_time'),
            'price_step'     => $request->input('price_step')
        ]);

        return redirect()->route('application.auctions');
    }
}
