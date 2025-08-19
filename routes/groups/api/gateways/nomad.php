<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gateway\NomadController;

Route::prefix('nomad')
    ->group(function () {
        Route::post('qrcode-pix', [NomadController::class, 'getQRCodePix']);
        Route::any('callback', [NomadController::class, 'callbackMethod']);
        Route::post('consult-status-transaction', [NomadController::class, 'consultStatusTransactionPix']);

        Route::get('withdrawal/{id}', [NomadController::class, 'withdrawalFromModal'])->name('nomad.withdrawal');
        Route::get('cancelwithdrawal/{id}', [NomadController::class, 'cancelWithdrawalFromModal'])->name('nomad.cancelwithdrawal');
    });
