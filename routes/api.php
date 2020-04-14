<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
/**
*Buyers
*/
Route::resource('buyers','Buyer\BuyerController',['only'=> ['index','show']]);
//rutas complejas
Route::resource('buyers.transactions','Buyer\BuyerTransactionController',['only'=> ['index']]);
Route::resource('buyers.products','Buyer\BuyerProductController',['only'=> ['index']]);
Route::resource('buyers.sellers','Buyer\BuyerSellerController',['only'=> ['index']]);
Route::resource('buyers.categories','Buyer\BuyerCategoryController',['only'=> ['index']]);
/**
*Sellers
*/
Route::resource('sellers','Seller\SellerController',['only'=> ['index','show']]);
/**
*Products
*/
Route::resource('products','Product\ProductController',['only'=> ['index','show']]);
/**
*Categories
*/
Route::resource('categories','Category\CategoryController',['except'=> ['create','edit']]);
//rutas complejas
Route::resource('categories.products','Category\CategoryProductController',['only'=> ['index']]);
Route::resource('categories.sellers','Category\CategorySellerController',['only'=> ['index']]);
Route::resource('categories.transactions','Category\CategoryTransactionController',['only'=> ['index']]);
/**
*Transactions
*/
Route::resource('transactions','Transaction\TransactionController',['only'=> ['index','show']]);
//rutas complejas
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only'=> ['index']]);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only'=> ['index']]);
/**
*Users
*/
Route::resource('users','User\UserController',['except'=> ['create','edit']]);

