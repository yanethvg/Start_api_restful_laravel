<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $seller = $buyer->transactions()->with('product.seller')
        ->get()
        ->pluck('product.seller') //para acceder a seller que se encuentra dentro de comprador
        ->unique('id') // para eliminar repetidos
        ->values(); // esto es para eliminar vacios
        //dd($seller);

        return $this->showAll($seller);
    }
}
