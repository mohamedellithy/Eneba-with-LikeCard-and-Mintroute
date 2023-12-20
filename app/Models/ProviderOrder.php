<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_auction_id',
        'provider_order_id',
        'provider_name',
        'response'
    ];

    public function auction_details(){
        return $this->belongsTo(Auction::class,'order_auction_id','id');
    }
}
