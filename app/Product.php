<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    //especificando que el atributo es de tipo fecha
    protected $dates = ['deleted_at'];
    
	const PRODUCT_DISPONIBLE = 'disponible';
	const PRODUCT_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id',
    ];

    //excluir la información del pivote 
    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
    	return $this->status == Product::PRODUCT_DISPONIBLE;
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
