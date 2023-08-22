<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnebaOrder extends Model
{
    use HasFactory;

    protected $fillable = ['status_order','order_id'];

    public function auctions(){
        return $this->belongsToMany(Auction::class,'eneba_order_auctions','eneba_order_id','eneba_auction_id')->using(EnebaOrderAuction::class);
    }
}
