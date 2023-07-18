<?php
namespace App\Services\LikeCard;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
class LikeCard {
    protected $sandbox       = false;
    public   $endpoint       = "https://taxes.like4app.com";
    public $credentail    = array();
    public function __construct(){
        $this->credentail = $this->fetch_likecard_crediential();
    }

    public function resolve_call($path,$query,$method = 'post'){
        $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/json',
        ])->withOptions([
            "verify"=>false
        ])->$method($this->endpoint.$path, $query);

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
        if($response->successful()):
            return $response->json();
        endif;
        return $response->status();
    }

    public function get_orders($page = 1){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'page'         => $page
        ];

        $response = $this->resolve_call('/online/orders',$credentail);
        if($response->successful()):
            return $response->json();
        endif;
    }

    public function get_categories($page = 1){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'page'         => $page
        ];

        $response = $this->resolve_call('/online/categories',$credentail);
        if($response->successful()):
            return $response->json();
        endif;
    }

    public function get_products($page = 1){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'categoryId'   => '376',
        ];

        $response = $this->resolve_call('/online/products',$credentail);
        if($response->successful()):
            return $response->json()['data'];
        endif;
    }


    public function get_single_product($product_id){

    }



}
