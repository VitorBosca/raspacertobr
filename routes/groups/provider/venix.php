<?php

use App\Http\Controllers\Api\Games\GameController;
use Illuminate\Support\Facades\Route;

Route::post('venixcg/webhook', [GameController::class, 'webhookVeniXMethod']);
