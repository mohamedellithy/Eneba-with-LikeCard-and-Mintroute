<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = ['auction','product_id','status','current_price','min_price','max_price','automation','change_time','price_step'];

    public function orders(){
        return $this->belongsToMany(EnebaOrder::class)->using(EnebaOrderAuction::class);
    }
}
