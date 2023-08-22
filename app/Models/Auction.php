<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = ['auction','product_id','status','current_price','count_cards','min_price','max_price','automation','change_time','price_step'];

    public function orders(){
        return $this->belongsToMany(EnebaOrder::class)->using(EnebaOrderAuction::class);
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','eneba_prod_id');
    }
}
