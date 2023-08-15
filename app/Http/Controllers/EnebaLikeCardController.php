<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LikeCard\LikeCard;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Cache;
class EnebaLikeCardController extends Controller
{
    //

    protected $application;
    protected $likecard_service;
    protected $eneba_service;

    public function __construct(){
        $this->likecard_service = new LikeCard();
        $this->eneba_service = new Eneba(true);
    }

    public function get_single_product(Request $request,$id){
        // $product_eneba  = Cache::rememberForever('eneba_single_product_'.$id, function() use($id){
        //     return $this->eneba_service->get_single_product($id)['result']['data'];
        // });

        dd( $this->eneba_service->sandbox_trigger_stock_provision() );

        $category      = null;
        if(request('category_id')):
            $category  = $request->query('category_id');
        endif;
        $categories = Cache::rememberForever('likecard_categories', function(){
            $categories = $this->likecard_service->get_categories();
            return isset($categories['data']) ? $categories['data'] : [];
        });

        $products = Cache::rememberForever('likecard_products_'.$category, function() use($category){
            $products   = $this->likecard_service->get_products($category);
            if(isset($products['data'])):
                if($products['response'] == 1):
                    $products = isset($products['data']) ? $products['data']: [];
                    return $products;
                endif;
            endif;

            return null;
        });

        return view('pages.eneba.products.show',compact('product_eneba','categories','products'));
    }
}
