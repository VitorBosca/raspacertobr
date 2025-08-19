<?php
use App\Http\Controllers\Gateway\PradaPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('pradapay')
    ->group(function ()
    {
        Route::post('qrcode-pix', [PradaPayController::class, 'getQRCodePix']);
        Route::post('consult-status-transaction', [PradaPayController::class, 'consultStatusTransactionPix']);
        
        Route::middleware(['admin.filament', 'admin'])
            ->group(function ()
            {
                Route::get('withdrawal/{id}/{action}', [PradaPayController::class, 'withdrawalFromModalPradaPay'])->name('pradapay.withdrawal');
                Route::get('cancelwithdrawal/{id}/{action}', [PradaPayController::class, 'cancelWithdrawalFromModalPradaPay'])->name('pradapay.cancelwithdrawal');
            });
    });



