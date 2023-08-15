<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = ['auction','product_id'];

    public function orders(){
        return $this->belongsToMany(EnebaOrder::class)->using(EnebaOrderAuction::class);
    }
}
