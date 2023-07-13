<?php
namespace App\Services\LikeCard;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
class LikeCard {
    protected $sandbox       = false;
    public   $endpoint       = "https://taxes.like4app.com";
    protected $credentail    = array();
    public function __construct(){
        $this->credentail = $this->fetch_likecard_crediential();
    }

    public function resolve_call($path,$query,$method = 'post'){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withOptions([
            "verify"=>false
        ])->$method($this->endpoint.$path,[
            'query' => $query
        ]);

        return $response;
    }


    public function fetch_likecard_crediential(){
        $application       = ApplicationSetting::where([
            'application'  => 'likecard'
        ])->pluck('value','name')->toArray();

        return $application;
    }

    public function get_balance(){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1'
        ];

        $response = $this->resolve_call('/online/check_balance',$credentail);
        dd($response->body());
    }

    public function get_products(){
        
    }


    public function get_single_product($product_id){
        
    }



}
