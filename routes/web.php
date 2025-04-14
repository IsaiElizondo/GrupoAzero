<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckOrderController;
use App\Http\Controllers\ArchivedController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\CancelationsController;
use App\Http\Controllers\RebillingsController;
use App\Http\Controllers\DebolutionsController;
use App\Http\Controllers\ShipmentsController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\Pedidos2Controller;


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

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/homesearch', [WelcomeController::class, 'search'])->name('homesearch');

//Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/prueba', [HomeController::class, 'prueba']);

Auth::routes();

Route::get('/home', [Pedidos2Controller::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('checkorders', CheckOrderController::class);
    Route::resource('archived', ArchivedController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('pictures', PictureController::class);
    Route::resource('follows', FollowsController::class);
    Route::resource('cancelations', CancelationsController::class);
    Route::resource('rebillings', RebillingsController::class);
    Route::resource('debolutions', DebolutionsController::class);
    Route::resource('shipments', ShipmentsController::class);

    Route::get('serach', [HomeController::class, 'search'])->name('search');
    Route::get('/picture', [HomeController::class, 'picture'])->name('picture');    //FOTO ENTREGADA
    Route::get('/cancelation', [HomeController::class, 'cancelation'])->name('cancelation');

    // Rutas cancelación, refacturación y devolución
    Route::get('/shipmentEvidence', [ShipmentsController::class, 'shipmentEvidence'])->name('shipment.evidence'); //EVIDENCIA DE ENVIO

    Route::get('/cancelEvidance', [CancelationsController::class, 'cancelEvidence'])->name('cancel.evidence'); //EVIDENCIA DE CANCELACION
    Route::get('/cancelRepayment', [CancelationsController::class, 'cancelRepayment'])->name('cancel.repayment'); //REEMBOLSO DE CANCELACION

    Route::get('/rebillingEvidence', [RebillingsController::class, 'rebillingEvidence'])->name('rebilling.evidence'); //EVIDENCIA DE REEMBOLSO
    Route::get('/rebillingRepayment', [RebillingsController::class, 'rebillingRepayment'])->name('rebilling.repayment'); //REEMBOLSO DE REEMBOLSO

    Route::get('/debolutionEvidence', [DebolutionsController::class, 'debolutionEvidence'])->name('debolution.evidence'); //EVIDENCIA DE DEVOLUCION
    Route::get('/debolutionRepayment', [DebolutionsController::class, 'debolutionRepayment'])->name('debolution.repayment'); //REEMBOLSO DE DEVOLUCION

    Route::get('/order/attachlist', [OrderController::class, 'attachlist'])->name('order.attachlist');
    Route::post('/order/attachpost', [OrderController::class, 'attachpost']);

    Route::get('/order/attachdev', [OrderController::class, 'attachdev']);
    Route::get('/order/attachdelete', [OrderController::class, 'attachdelete']);

    Route::get('/order/debolutioncreatefor', [OrderController::class, 'debolutioncreatefor']);
    Route::get('/order/partialcreatefor', [OrderController::class, 'partialcreatefor']);

});

require_once "pedidos2.php";
require_once "reportes.php";
require_once "dev.php";

// Para generar el storage link
// Route::get('storage-link', function(){
//     Artisan::call('storage:link');
// });
