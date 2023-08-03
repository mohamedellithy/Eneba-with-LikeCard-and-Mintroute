<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'product_image',
        'category_id',
        'product_type',
        'code',
        'status_used',
        'status'
    ];
}
