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
/**
*Transactions
*/
Route::resource('transactions','Transaction\TransactionController',['only'=> ['index','show']]);
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only'=> ['index']]);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only'=> ['index']]);
/**
*Users
*/
Route::resource('users','User\UserController',['except'=> ['create','edit']]);

