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
}
