<?php

use App\Http\Controllers\Gateway\SharkPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('sharkpay')
    ->group(function ()
    {
        Route::post('qrcode-pix', [SharkPayController::class, 'getQRCodePix']);
        Route::post('consult-status-transaction', [SharkPayController::class, 'consultStatusTransactionPix']);
    });



