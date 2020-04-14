<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
                        ->whereHas('transactions') //estrae solo aquellos que tienen una transacción unicamente con esto se asegura de que tengan transacciones los productos
                        ->with('transactions')
                        ->get()
                        ->pluck('transactions')
                        ->collapse()
                        ->unique('id')
                        ->values();
        return $this->showAll($transactions);
    }

   
}
