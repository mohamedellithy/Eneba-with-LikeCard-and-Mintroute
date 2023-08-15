<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnebaOrder extends Model
{
    use HasFactory;

    protected $fillable = ['status_order','order_id','product_id','auctions'];
}
