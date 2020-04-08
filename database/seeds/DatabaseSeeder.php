<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        //desactivando llaves foraneas
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        //truncando la base de datos
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuarios = 200;
        $cantidadCategories = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        //creando usuarios
        factory(User::class,$cantidadUsuarios)->create();
        //creando Categorias
        factory(Category::class,$cantidadCategories)->create();
        //creando Productos
        factory(Product::class,$cantidadProductos)->create()->each(
        	function($producto) {
        		$categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
        		$producto->categories()->attach($categorias);
        	}
        );
        //creando transacciones
        factory(Transaction::class,$cantidadTransacciones)->create();
    }
}
