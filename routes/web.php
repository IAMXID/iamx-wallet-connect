<?php

use IAMXID\IamxWalletConnect\Http\Controllers\WalletConnectController;
use Illuminate\Support\Facades\Route;
Route::middleware('web')->group(function () {
    Route::post('/iamx/connect_identity', [WalletConnectController::class, 'connectIdentity']);
    Route::get('/iamx/disconnect_identity', [WalletConnectController::class, 'disconnectIdentity']);
});

Route::get('/iamx/get_identity_scope', [WalletConnectController::class, 'getIdentityScope']);

