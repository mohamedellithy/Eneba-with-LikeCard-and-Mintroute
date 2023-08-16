<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
class AuctionApplication extends Controller
{
    //

    public function index(Request $request){
        $auctions = Auction::paginate(20);
        return view('pages.auctions.index',compact('auctions'));
    }

    public function ajax_search_on_eneba_products(Request $request){
        return response()->json([
            'result' => 'hi mohamed'
        ]);
    }
}
