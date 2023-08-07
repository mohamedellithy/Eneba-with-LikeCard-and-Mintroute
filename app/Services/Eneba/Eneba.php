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

        $token = $this->generate_token();

        $this->credentail['access_token'] = ($token['status'] == 'success') ? $token['access_token'] : null;
    }

    public function resolve_call($query){
        if($this->sandbox == true):
            $endpoint = $this->endpoint.'/graphql/';
        else:
            $endpoint = $this->endpoint.'/';
        endif;
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->credentail['access_token'],
            'Content-Type' => 'application/json',
        ])->withOptions([
            "verify"=>false
        ])->post($endpoint,[
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

        if($this->sandbox == true):
            $endpoint = $this->endpoint;
        else:
            $endpoint          = "https://user.eneba.com";
            $post['client_id'] = "917611c2-70a5-11e9-97c4-46691b78bfa2";
        endif;

        $response = Http::asForm()->withOptions([
            "verify"=>false
        ])->post($endpoint.'/oauth/token',$post);


        // dd($response->body(),$post);

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

    public function get_products($from = null,$search = ""){
        $now = date('Y-m-d H:i:s');
        $query = <<<GQL
        query {
            S_products(
                sort: CREATED_AT_ASC
                first:20
                after:"{$from}"
                search:"{$search}"
              ) {
              totalCount
              pageInfo {
                  hasNextPage
                  hasPreviousPage
                  startCursor
                  endCursor
              }
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

        if($response->successful()):
            return [
              'code' => $response->status(),
              'status' => isset($response->json()['data']['S_products']) ? true : false,
              'result' => isset($response->json()['data']['S_products']) ? $response->json()['data']['S_products'] : $response->json()
            ];
        else:
          return [
              'code' => $response->status(),
              'status' => false,
              'result' => $response->json()
          ];
        endif;
    }


    public function get_single_product($product_id){
        $query = <<<GQL
        query {
            S_product(productId: "{$product_id}") {
              name
              slug
              id
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

        if($response->successful()):
            return [
                'code' => $response->status(),
                'status' => isset($response->json()['data']['S_products']) ? true : false,
                'result' => isset($response->json()['data']['S_products']) ? $response->json()['data']['S_products'] : $response->json()
            ];
        endif;
    }

    public function declared_stock(){
        // $query = <<<GQL
        //     mutation {
        //         P_registerCallback(
        //         input: {
        //             type: DECLARED_STOCK_PROVISION
        //             url: "https://a-ecards.com/applications/eneba/callback-stock-provision"
        //             authorization: "eW91ci1hdXRob3JpemF0aW9uLWhlYWRlcg=="
        //         }
        //         ) {
        //         success
        //         }
        //     }
        // GQL;
        // $response = $this->resolve_call($query);
        // dd($response->json());

        $query = <<<GQL
            mutation {
                P_enableDeclaredStock {
                success
                failureReason
                }
            }
        GQL;
        $response = $this->resolve_call($query);

        dd($response->json());

        $query = <<<GQL
            mutation {
                P_triggerCallback(input: {
                type: DECLARED_STOCK_RESERVATION
                orderId: "347c4978-4f81-11ed-bdc3-0242ac120002"
                auction: {
                    auctionId: "347c4e96-4f81-11ed-bdc3-0242ac120002"
                    price: {
                    amount: 1500
                    currency: "EUR"
                    }
                    keyCount: 1
                }
                }){
                success
                message
                }
            }
        GQL;
        $response = $this->resolve_call($query);

        dd($response->json());


        $query = <<<GQL
            mutation {
                S_createAuction(
                input: {
                    productId: "beb58814-a15e-1de2-af2d-47633c765f13"
                    enabled: true
                    declaredStock: 1
                    autoRenew: false
                    price: { amount: 1400, currency: "EUR" }
                }
                ) {
                success
                actionId
                }
            }
        GQL;

        $response = $this->resolve_call($query);

        dd($response->json());

        if($response->successful()):
            return [
                'code' => $response->status(),
                'result' => isset($response->json()['data']['S_products']) ? $response->json()['data']['S_products'] : $response->json()
            ];
        endif;
    }

    public function register_callback(){
        $query = <<<GQL
            mutation {
                P_registerCallback(
                input: {
                    type: DECLARED_STOCK_RESERVATION
                    url: "https://a-ecards.com/applications/application/eneba/callback-stock-reservation"
                    authorization: "eW91ci1hdXRob3JpemF0aW9uLWhlYWRlcg=="
                }
                ) {
                success
                }
            }
        GQL;
        $response = $this->resolve_call($query);

        /************************************************************* */
        /************************************************************* */
        /************************************************************* */

        $query = <<<GQL
            mutation {
                P_registerCallback(
                input: {
                    type: DECLARED_STOCK_PROVISION
                    url: "https://a-ecards.com/applications/eneba/callback-stock-provision"
                    authorization: "eW91ci1hdXRob3JpemF0aW9uLWhlYWRlcg=="
                }
                ) {
                success
                }
            }
        GQL;
        $response = $this->resolve_call($query);

        dd($response->json());
    }
}
