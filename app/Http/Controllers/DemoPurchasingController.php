<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\RenewStockEneba;
use App\Services\Eneba\Eneba;
use App\Models\ApplicationSetting;
use App\Services\LikeCard\LikeCard;
use Illuminate\Support\Facades\Http;

class DemoPurchasingController extends Controller
{
    //
    protected $application;
    protected $eneba_service;

    public function __construct(){
        $this->application   = 'sandbox_eneba';
        $this->eneba_service = new Eneba($sandbox = true);
    }
    public function index(Request $request){
        // $message = "hellow mohamed";
        // RenewStockEneba::dispatch($message);
        // echo "hi";
        // $eneba  = new Eneba(false);
        // $result = $eneba->fetch_single_auction("f6060584-8f95-11ee-8741-2ef471adb63c");

        // dd($result);
        // $request->merge([
        //     'orderId'  => '347c4978-4f81-11ed-bdc3-0242ac120002'
        // ]);

        //$this->eneba_service->eneba_callback_stock_provision();
        // 7d0462a2-fdec-11ec-9ceb-b62153817ae7
        // 9fea8e4c-c54a-11e8-a803-186590d66063
        // $result = GetMyAuctions("7d0462a2-fdec-11ec-9ceb-b62153817ae7");
        // dd($result);

        
        // dd($this->eneba_service->register_stock_provision());
       //- dd($this->eneba_service->get_callbacks_registered());
       
       
        // $this->generate_token();
        // var_dump($this->eneba_service->credentail);
        // $this->eneba_service->sandbox_trigger_stock_reservation();

        $query = <<<GQL
            mutation {
                P_triggerCallback(input: {
                type: DECLARED_STOCK_RESERVATION
                orderId: "347c4978-4f81-11ed-bdc3-0242ac120002"
                auction: {
                    auctionId: "347c4e96-4f81-11ed-bdc3-0242ac120002"
                    price: {
                    amount: 50
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

        $response = Http::withHeaders([
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJjNjk5ZWM4OS1jNWZkLTRiYjAtODUyZS1lYWU3NzUxOTg1ODciLCJqdGkiOiI0YjNlNDRmMi05NTUzLTQ0MjQtYjAyOC03NGUwYWZiNDJhMjkiLCJpYXQiOjE3MTQwODQyMTYuOTkwNjI2LCJuYmYiOjE3MTQwODQyMTYuOTkwNjI2LCJleHAiOjE3MTQzMDAyMTYuOTkwNjI2LCJzdWIiOiJlY2FjM2EyZS0xOWZlLTExZWUtYWQ3ZS0xNmIyOWM5NDQ3ODEiLCJzY29wZSI6ImFwaV9jb25zdW1lciIsInJvbGVzIjpbIlJPTEVfVVNFUiIsIlJPTEVfQVBJX0NPTlNVTUVSIl0sInByZWZlcnJlZF91c2VybmFtZSI6IkIuREpBU1NFTUBBLUVDQVJEUy5DT00iLCJlbWFpbCI6IkIuREpBU1NFTUBBLUVDQVJEUy5DT00ifQ.HwOyIkS4USQt5el6qdDieCNQO_oGsbCV7B9GCK0YKwSOPzXBRTcb_cbIE5AMw6pF8PRvenTn2URZCYTshRV6lzPPlqNCQ-L6A0MwLUy1qVUi3eDJvA4HPox_k68HVF7ASwTWNS-PfGQrhAWABwEE7k-BzYKxhNvCsSSC7ci70bY',
            'Content-Type'  => 'application/json',
        ])->withOptions([
            "verify"=>false
        ])->post('https://api-sandbox.eneba.com/graphql/',[
            'query' => $query
        ]);

       dd($response->body());




        // $this->eneba_service->sandbox_trigger_stock_reservation();
        //dd($this->eneba_service->sandbox_trigger_stock_provision());
        // $likecard = new LikeCard();
        // $codes = [
        //     "MCttSkNob2NzMjg0SGcxYkFCcTAydU5WYjN4TnJsaWpSMTJGWEdSY1piWT0=",
        //     "eU4xSTIzNjRWaUpEVXdLOHc5S1pFbUlGbUZDTmlYVjhkWC95VEt0cUc1UT0=",
        //     "QXpoaHNpVzM1WnUyTWptM01vb1FuY1lUWnA0KzZvV2tRSTM3NHpheUZoRT0=",
        //     "ZzM0UndJWC95NmtZa0RkcVJFT2pJemd4dTJHdS82SXdlRVkzNWhuanhnbz0=",
        //     "NVVJVGFnTU5DeUNlUjZMLzBkOWg1bHJad3h5Yjg5bWVLaklNUi9oaGg5ND0=",
        //     "alZrc3ptZ1lJUDZVQUxnaEg3MUIyZmdsaU5tODhIQTVla1BNaGh1elNiaz0=",
        //     "Wnc4V2NFN3VqTkdzSHV6WmdPV0ZzUlpqVTZoNXRjckR6Mk5TbDdreUxyYz0=",
        //     "WXBteVdoQXV6cUtZZDV6MyswSDdFcXFEd0NYK2t2Y3BwVmNXVFhxMEtvaz0=",
        //     "N1dEYUwvc1JhQTFYNFJEZ0pxQ256V0FNNEFBM0ppODlCcVlKSXl3amxmRT0=",
        //     "NWJ0NmlYbHZDNmNyRXpTWmU1RVpKbDFIaUV2YnBFWVVYOHovYjU1OFVOUT0=",
        //     "SXNwdlBqb09hYStzRFRhb0ZZMnBUL3ZLai9Vc3g1QWxVallLUGQzYVRtdz0=",
        //     "Qm1VZWRLZkNNMlBBTndKVlc4UDV6dytFN1RaMGxOdzgzRGIrdWc3MmNVST0=",
        //     "QWg4NVdVT25CODJpSndqU1BNd2VCZUJubFhzVnNSc2V6UTBOYTZBMU5GQT0=",
        //     "bUd0eFhlaFdsdXkvNkpVbjNyclhmYWQ2VDBCYkUwRW5KdkdRdS9zUGVXYz0=",
        //     "SFJ0VGxjMW5uSnZNV3NydCs4WGFzcjBKSE12MnhkUVVDVUdLakpiUFBGOD0=",
        //     "MG5uVFk2S05DSDN5NjFhOERwVzE5bzNJRlVKSCtCNU1DSFRtMlNrQ0h5bz0=",
        //     "cmxhOHc4ZHkxdzdJQU95RXhpOG12QUYxM3g4RS8vQmxERllCQ3J0ZlBPST0=",
        //     "bUFib3JHazE1UDJYdlF4c1VvVUtPWXhPaExtNGV6Q0tvNjdiMjNFcndhWT0=",
        //     "V1ZlU0tDTHBQUmlwdnp3V2R6eHNjcWIrcGNocWxLV1ZOODFtTWRjOHNuST0="
        // ];

        // foreach($codes as $code):
        //     $response [] = $likecard->decryptSerial($code);
        // endforeach;
        // dd($response);
        // dd($this->eneba_service->eneba_callback_stock_provision());
    }

    public function generate_token(){
        $eneba = $this->eneba_service->generate_token();
        if(isset($eneba['refresh_token'])):
            ApplicationSetting::updateOrCreate([
                'application' => $this->application,
                'name'        => 'refresh_token'
            ],[
                'value'       => $eneba['refresh_token']
            ]);
        endif;

        if(isset($eneba['access_token'])):
            ApplicationSetting::updateOrCreate([
                'application' => $this->application,
                'name'        => 'access_token'
            ],[
                'value'       => $eneba['access_token']
            ]);
        endif;
    }

}
