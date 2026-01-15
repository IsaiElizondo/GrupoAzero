<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SepomexController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('orders', function () {
//     return datatables()
// 		->eloquent(App\Order::query())
// 		->addColumn('btn', 'actions')
// 		->rawColumns(['btn'])
// 		->toJson();
// });

Route::get('sepomex/buscar-cp', [SepomexController::class, 'buscar_cp']);