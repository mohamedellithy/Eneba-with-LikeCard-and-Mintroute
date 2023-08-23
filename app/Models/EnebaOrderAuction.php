<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnebaOrderAuction extends Model
{
    use HasFactory;

    protected $fillable = ['eneba_order_id','eneba_auction_id','key_count_required','unit_price'];
}
