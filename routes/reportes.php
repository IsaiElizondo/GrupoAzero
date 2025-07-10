<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\ReportesController;

Route::group (['middleware' => 'auth'], function (){

    Route::get('reportes', [ReportesController::class, 'index'])->name('reportes');
    
    Route::get('reportes/subprocesos', [ReportesController::class, 'subprocesos']);
    Route::get('reportes/participaciones', [ReportesController::class, 'participaciones']);
    Route::get('reportes/feed_usuarios', [ReportesController::class, 'feed_usuarios']);
    
    Route::post('reportes/reporte_subprocesos', [ReportesController::class, 'reporte_subprocesos']);    
    Route::post('reportes/reporte_participaciones', [ReportesController::class, 'reporte_participaciones']);
    
    Route::get('/dashboard/exportar-excel', [ReportesController::class, 'ExcelDashboard'])->name('dashboard.exportar.excel');
    Route::get('reportes/fabricacion-excel', [ReportesController::class, 'ExcelFabricacion'])->name('reportes.fabricacion-excel');


});