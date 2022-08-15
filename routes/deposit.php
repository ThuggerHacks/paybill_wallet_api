<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DepositController;

Route::post("/deposit/make",[DepositController::class, "index"])->middleware("api_auth","email_verification");

Route::get("/deposit/all",[DepositController::class, "deposit_all"])->middleware("api_auth","email_verification","is_admin");

Route::get("/deposit/{id?}",[DepositController::class, "deposit_by_id"])->middleware("api_auth","email_verification");

Route::get("/deposit/wallet/{wallet_id?}",[DepositController::class, "deposit_by_wallet_id"])->middleware("api_auth","email_verification");

//Route::put("/deposit/update/{id?}",[DepositController::class, "deposit_update"])->middleware("api_auth","email_verification");

Route::delete("/deposit/delete/{id?}",[DepositController::class, "deposit_delete"])->middleware("api_auth","email_verification","is_admin");