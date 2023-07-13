<?php
namespace App\Services\Eneba;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;
class Eneba {
    protected $sandbox       = false;
    public   $endpoint      = "";
    protected $credentail    = array();
    public function __construct($sandbox = true){
        $this->sandbox    = $sandbox;

        $this->endpoint   = $this->sandbox == true ? "https://api-sandbox.eneba.com" : "https://api.eneba.com";

        $this->credentail = $this->fetch_enebe_crediential();
    }

    public function resolve_call($query){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->credentail['access_token'],
            'Content-Type' => 'application/json',
        ])->withOptions([
            "verify"=>false
        ])->post($this->endpoint.'/graphql/',[
            'query' => $query
        ]);

        return $response;
    }


    public function generate_token(){
        $post = [
            'grant_type' => 'api_consumer',
            'client_id'  => '917611c2-70a5-11e9-00c4-ee691bb8bfaa',
            'id'         => $this->credentail['auth_id'],
            'secret'     => $this->credentail['auth_secret']
        ];

        $response = Http::asForm()->withOptions([
            "verify"=>false
        ])->post($this->endpoint.'/oauth/token',$post);

        if($response->successful() == true):
            return [
                'code'         => $response->status(),
                'status'       => 'success',
                'access_token' => $response->json()["access_token"],
            ];
        endif;

        return [
            'code'       => $response->status(),
            'status'     => 'failed'
        ];
    }

    public function fetch_enebe_crediential(){
        $credential        = [];
        $application       = ApplicationSetting::where([
            'application'  => 'eneba'
        ])->pluck('value','name')->toArray();

        $status                = 'prod';
        if($this->sandbox == true):
            $status            = 'sandbox';
        endif;

        $credential['auth_id']      = isset($application[$status.'_auth_id']) ? $application[$status.'_auth_id'] : null;
        $credential['auth_secret']  = isset($application[$status.'_auth_secret']) ? $application[$status.'_auth_secret'] : null;
        $credential['access_token'] = isset($application['access_token']) ? $application['access_token'] : null;

        return $credential;
    }

    public function get_balance(){
        $query = <<<GQL
        query {
            B_balance {
                total { amount currency }
                payout { amount currency }
                onHold { amount currency }
                giftCard { amount currency }
                affiliate { amount currency }
                credit { amount currency }
                frozenBonus { amount currency }
            }
        }
        GQL;
        $response = $this->resolve_call($query);
        dd($response->json());
    }

    public function get_products(){
        $query = <<<GQL
        query {
            S_products(
              sort: CREATED_AT_DESC
              ) {
              edges {
                node {
                  id
                  name
                  languages
                  regions { code }
                  releasedAt
                  createdAt
                  slug
                  type { value }
                  auctions(first: 1) {
                    edges {
                      node {
                        belongsToYou
                        isInStock
                        merchantName
                        price { amount currency }
                      }
                    }
                  }
                }
              }
            }
          }
        GQL;
        $response = $this->resolve_call($query);
        dd($response->json());
    }


    public function get_single_product($product_id){
        $query = <<<GQL
        query {
            S_product(productId: "{$product_id}") {
              name
              slug
              regions { code }
              type { value }
              auctions {
                totalCount
                edges {
                  node {
                    belongsToYou
                    price { amount currency }
                  }
                }
              }
            }
        }
        GQL;
        $response = $this->resolve_call($query);
        dd($response->json());
    }



}
