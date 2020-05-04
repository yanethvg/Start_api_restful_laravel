<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
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
        /*iger loading se hace esto porque transaction asi nada mas 
        se debe acceder a la relación y mandar a llamar a with para que se aplique a cada transacción recordando que transaction sin los parentesis devuelve una transacción con ponerlo asi transactions() estamos accediendo a cada uno y el metodo with se aplica a cada una de las transacciones, pero como no queremos
        las transacciones sino solamente los productos se aplica pluck con product que es uno de los atributos de la collection
        */

        $products = $buyer->transactions()->with('product')
            ->get()
            ->pluck('product');
      
        return $this->showAll($products);
    }
}
