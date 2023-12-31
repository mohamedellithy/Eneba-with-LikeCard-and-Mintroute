<?php
namespace App\Services\LikeCard;
use Illuminate\Support\Str;
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

    public function get_products($category = '376'){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'categoryId'   => "{$category}",
        ];

        $response = $this->resolve_call('/online/products',$credentail);
        if($response->successful()):
            return $response->json();
        endif;
    }


    public function get_single_product($product_id){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'ids[]'        => $product_id,
        ];

        $response = $this->resolve_call('/online/products',$credentail);
        if($response->successful()):
            return $response->json();
        endif;
    }

    public function create_likecard_order($product_id,$qty = 1,$order_id = null){
        if(!$product_id) return null;
        $order_id   = $order_id ?: Str::random(5);
        $time       = strtotime(date('Y-m-d H:i:s'));
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'productId'    => $product_id,
            'quantity'     => $qty,
            'referenceId'  => 'Order_'.$order_id,
            'time'         => $time,
            'hash'         => $this->generateHash($time)
        ];

        //return json_encode($credentail);

        $response = $this->resolve_call('/online/create_order',$credentail);
        if($response->successful()):
            return $response->json();
        else:
            return null;
        endif;
    }

    public function create_bulk_likecard_order($product_id,$qty = 1,$order_id = null){
        if(!$product_id) return null;
        $order_id   = $order_id ?: Str::random(5);
        $time       = strtotime(date('Y-m-d H:i:s'));
        $products   = [
            [
                'productId'    => $product_id,
                'quantity'     => $qty
            ]
        ];
        $products   = json_encode($products);
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'products'     => $products,
            'referenceId'  => 'Order_'.$order_id,
            'time'         => $time,
            'hash'         => $this->generateHash($time)
        ];

        //return json_encode($credentail);

        $response = $this->resolve_call('/online/create_order/bulk',$credentail);
        if($response->successful()):
            return $response->json();
        else:
            return null;
        endif;
    }

    public function check_balance(){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1'
        ];

        //return json_encode($credentail);

        $response = $this->resolve_call('/online/check_balance',$credentail);
        if($response->successful()):
            return $response->json();
        else:
            return null;
        endif;
    }

    public function get_bulk_order($order_id = null){
        $credentail =  [
            'deviceId'     => isset($this->credentail['prod_deviceId']) ? $this->credentail['prod_deviceId'] : null,
            'email'        => isset($this->credentail['prod_email'])    ? $this->credentail['prod_email'] : null,
            'password'     => isset($this->credentail['prod_password']) ? $this->credentail['prod_password'] : null,
            'securityCode' => isset($this->credentail['prod_securityCode']) ? $this->credentail['prod_securityCode'] : null,
            'langId'       => '1',
            'bulkOrderId'  => $order_id
        ];

        $response = $this->resolve_call('/online/get_bulk_order',$credentail);
        if($response->successful()):
            return $response->json();
        else:
            return null;
        endif;
    }

    public function generateHash($time){
        $email = strtolower(isset($this->credentail['prod_email']) ? $this->credentail['prod_email'] : null);
        $phone = isset($this->credentail['prod_phone']) ? $this->credentail['prod_phone'] : null;
        $key   = isset($this->credentail['prod_hash_key']) ? $this->credentail['prod_hash_key'] : null;
        return hash('sha256',$time.$email.$phone.$key);
    }

    function decryptSerial($encrypted_txt){
        $secret_key = isset($this->credentail['prod_secret_key']) ? $this->credentail['prod_secret_key'] : null;
        $secret_iv  = isset($this->credentail['prod_secret_iv']) ? $this->credentail['prod_secret_iv'] : null;;
        $encrypt_method = 'AES-256-CBC';
        $key = hash('sha256', $secret_key);

        //iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        return openssl_decrypt(base64_decode($encrypted_txt), $encrypt_method, $key, 0, $iv);
    }

}
