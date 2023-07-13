<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\ApplicationSetting;
class ApplicationController extends Controller
{
    public function index_eneba(){
       $settings = ApplicationSetting::where('application','eneba')->pluck('value','name')->toArray();
       return view('pages.eneba.settings',compact('settings'));
    }

    public function index_likecard(){
        $settings = ApplicationSetting::where('application','likecard')->pluck('value','name')->toArray();
        return view('pages.likecard.settings',compact('settings'));
    }

    public function index_mintroute(){
        return view('pages.mintroute.settings');
    }
}
