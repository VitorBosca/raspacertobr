<?php


use App\Http\Controllers\Gateway\PradaPayController;
use Illuminate\Support\Facades\Route;


Route::prefix('pradapay')
    ->group(function () {
        Route::post('callback', [PradaPayController::class, 'callbackMethod']);
        Route::post('payment', [PradaPayController::class, 'callbackMethodPayment']);
        
        Route::middleware(['admin.filament', 'admin'])
            ->group(function ()
            {
                Route::get('withdrawal/{id}/{action}', [PradaPayController::class, 'withdrawalFromModalPradaPay'])->name('pradapay.withdrawal');
                Route::get('cancelwithdrawal/{id}/{action}', [PradaPayController::class, 'cancelWithdrawalFromModalPradaPay'])->name('pradapay.cancelwithdrawal');
            });
    });
