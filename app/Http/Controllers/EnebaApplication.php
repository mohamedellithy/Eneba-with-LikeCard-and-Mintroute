<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\ApplicationSetting;
use App\Services\Eneba\Eneba;
class EnebaApplication extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'eneba';
        $this->eneba_service = new Eneba();
    }

    public function update_credentials(Request $request,$section){

        if($section == 'sandbox'):
            $credintal = [
                'sandbox_auth_id',
                'sandbox_auth_secret'
            ];
        elseif($section == 'prod'):
            $credintal = [
                'prod_auth_id',
                'prod_auth_secret'
            ];
        endif;

        foreach($request->only($credintal) as $name => $value):
            $main = [
                'application' => $this->application,
                'name'        => $name
            ];

            $data = [
                'value'       => $value
            ];

            ApplicationSetting::updateOrCreate($main,$data);
        endforeach;


        flash('Application is settings saved successfully','success');
        return back();
    }


    public function generate_token(){
        $eneba = (new Eneba())->generate_token();
        ApplicationSetting::updateOrCreate([
            'application' => $this->application,
            'name'        => 'access_token'
        ],[
            'value'       => $eneba['access_token']
        ]);

        flash('Application is settings saved successfully','success');

        return back();
    }
}
