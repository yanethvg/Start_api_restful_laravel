<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
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
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
                        ->whereHas('transactions') //estrae solo aquellos que tienen una transacción unicamente con esto se asegura de que tengan transacciones los productos
                        ->with('transactions')
                        ->get()
                        ->pluck('transactions')
                        ->collapse()
                        ->unique('id')
                        ->values();
        //dd($transactions);
        return $this->showAll($transactions);
    }

}
