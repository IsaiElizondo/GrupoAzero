<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\ReportesController;

Route::group (['middleware' => 'auth'], function (){

    Route::get('resportes', [ReportesController::class, 'index'])->name('reportes');
    
    Route::get('reportes/subprocesos', [ReportesController::class, 'subprocesos']);
    Route::get('reportes/participaciones', [ReportesController::class, 'participaciones']);
    Route::get('reportes/feed_usuarios', [ReportesController::class, 'feed_usuarios']);
    
    Route::post('reportes/participaciones', [ReportesController::class, 'participaciones_post']);
    Route::post('reportes/feed_usuarios', [ReportesController::class, 'feed_usuarios_post']);

});