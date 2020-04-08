<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    	'quantity',
    	'buyer_id',
    	'product_id',
    ];
    public function buyer()
    {
    	$this->belongsTo(Buyer::class);
    }
    public function product()
    {
    	$this->belongsTo(Product::class);
    }
}
