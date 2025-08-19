<?php

use App\Http\Controllers\Api\Games\ScratchCardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:api'])
    ->prefix('scratch-card')
    ->group(function () {
        Route::post('/buy', [ScratchCardController::class, 'buyScratchCard']);
        Route::post('/result', [ScratchCardController::class, 'processScratchResult']);
        Route::get('/balance', [ScratchCardController::class, 'getUserBalance']);
        Route::get('/history', [ScratchCardController::class, 'getGameHistory']);
        Route::get('/not-finalized', [ScratchCardController::class, 'getNotFinalizedGames']);
        Route::post('/create-demo', [ScratchCardController::class, 'createDemoGame']);
    });

Route::middleware(['api'])
    ->prefix('scratch-card')
    ->group(function () {
        Route::get('/prizes/{gameId}', [ScratchCardController::class, 'getGamePrizes']);
    });