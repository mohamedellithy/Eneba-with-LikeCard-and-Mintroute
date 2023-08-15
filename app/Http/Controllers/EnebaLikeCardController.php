<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LikeCard\LikeCard;
use App\Services\Eneba\Eneba;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
class EnebaLikeCardController extends Controller
{
    //

    protected $application;
    protected $likecard_service;
    protected $eneba_service;

    public function __construct(){
        $this->likecard_service = new LikeCard();
        $this->eneba_service = new Eneba($sandbox = false);
    }

    public function get_single_product(Request $request,$id){
        $product_eneba  = Cache::rememberForever('eneba_single_product_'.$id, function() use($id){
            return $this->eneba_service->get_single_product($id)['result']['data'];
        });

        $eneba_likecard_product = Product::where('eneba_prod_id',$id)->value('likecard_prod_id');

        $likecard_product_info = null;
        if($eneba_likecard_product):
            $likecard_product_info =  Cache::rememberForever('likecard_product_'.$eneba_likecard_product, function() use($eneba_likecard_product){
                return $this->likecard_service->get_single_product($eneba_likecard_product);
            });
        endif;

        dd($likecard_product_info);

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


        return view('pages.eneba.products.show',compact('product_eneba','categories','products','eneba_likecard_product','likecard_product_info'));
    }

    public function attach_eneba_likecard(Request $request,$eneba_id,$likecard_id){
        Product::updateOrCreate([
            'eneba_prod_id'    => $eneba_id,
            'likecard_prod_id' => $likecard_id
        ]);

        return back();
    }
}
