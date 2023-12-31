<?php

use tayyabtahir71\MT5WebApi\Controllers;
use Illuminate\Support\Facades\Route;
use tayyabtahir71\MT5WebApi\MTConSymbol;
// use tayyabtahir71\MT5WebApi\MTWebAPI;
use Carbon\CarbonImmutable;

// Route::get('inspire', [Controllers\InspirationController::class, 'index']);



Route::prefix('mt5')->group(["namespace"=>"MT5WebApi"],function() {
    Route::get("/","Controllers\MT5Controller@index");
    Route::get('/account/{account}', 'Controllers\MT5Controller@account');
});