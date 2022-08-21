<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WalletController;


Route::post("/wallet/create",[WalletController::class, "index"])->middleware("api_auth","email_verification");

Route::post("/wallet/activate/{id?}",[WalletController::class, "wallet_activate"])->middleware("api_auth","email_verification");

Route::get("/wallet/all",[WalletController::class, "wallet_all"])->middleware("api_auth","email_verification","is_admin");

Route::get("/wallet/user",[WalletController::class, "wallet_user_id"])->middleware("api_auth","email_verification");

Route::get("/wallet/{wallet_id?}",[WalletController::class, "wallet_by_id"])->middleware("api_auth","email_verification");


Route::put("/wallet/update/title/{id?}",[WalletController::class, "update_wallet"])->middleware("api_auth","email_verification");

Route::put("/wallet/update/phone/{id?}",[WalletController::class, "update_associated_phone_number"])->middleware("api_auth","email_verification");

Route::delete("/wallet/delete/{id?}",[WalletController::class, "delete_wallet"])->middleware("api_auth","email_verification");