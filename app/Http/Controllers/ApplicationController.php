<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
class ApplicationController extends Controller
{
    public function index_eneba(){
        $settings = ApplicationSetting::where('application','eneba')->pluck('value','name')->toArray();
        return view('pages.eneba.settings',compact('settings'));
    }

    public function index_likecard(){
        $page        = request('page') ?: 1;
        $like_card   = new LikeCard();
        $balance     = $like_card->get_balance();
        $orders      = $like_card->get_orders($page);
        $settings    = ApplicationSetting::where('application','likecard')->pluck('value','name')->toArray();

        //dd($orders);
        return view('pages.likecard.settings',compact('settings','balance','orders'));
    }

    public function index_mintroute(){
        return view('pages.mintroute.settings');
    }

    public function general_settings(){
        return view('pages.general-settings');
    }
}
