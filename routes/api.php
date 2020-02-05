<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('persons', 'API\PersonController')
    ->only(['index', 'show'])
    ->middleware('client');

Route::apiResource('orders', 'API\OrderController')
    ->only(['index', 'show'])
    ->middleware('client');
