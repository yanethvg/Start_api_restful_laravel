<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
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
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse() // esto es para juntar todas las listas en una sola
        ->unique('id') // para eliminar repetidos
        ->values(); // esto es para eliminar vacios
        //dd($categories);

        return $this->showAll($categories);
    }

}
