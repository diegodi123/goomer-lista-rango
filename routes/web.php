<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Restaurantes
Route::get('/restaurante/all', 'RestauranteController@all');
Route::post('/restaurante/inserir', 'RestauranteController@inserir');
Route::get('/restaurante/visualizar/{id}', 'RestauranteController@visualizar');
Route::post('/restaurante/editar/{id}', 'RestauranteController@editar');
Route::delete('/restaurante/deletar/{id}', 'RestauranteController@deletar');

//Produtos
Route::get('/produto/all/{id}', 'ProdutoController@all');
Route::post('/produto/inserir', 'ProdutoController@inserir');
Route::get('/produto/visualizar/{id}', 'ProdutoController@visualizar');
Route::post('/produto/editar/{id}', 'ProdutoController@editar');
Route::delete('/produto/deletar/{id}', 'ProdutoController@deletar');

Route::get('/', function () {
    return phpinfo();
});
