<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index(){
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        return view('pages.dashboard');
    }
}
