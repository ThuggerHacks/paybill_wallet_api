<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CashoutController;

Route::post("/withdraw",[CashoutController::class, "index"])->middleware("api_auth","email_verification");

Route::get("/withdraw/all",[CashoutController::class, "cash_all"])->middleware("api_auth","email_verification","is_admin");

Route::get("/withdraw/{id?}",[CashoutController::class, "cash_by_id"])->middleware("api_auth","email_verification");

Route::get("/withdraw/wallet/{wallet_id?}",[CashoutController::class, "cash_by_wallet_id"])->middleware("api_auth","email_verification");

//Route::put("/withdraw/update/{id?}",[CashoutController::class, "cash_update"])->middleware("api_auth","email_verification");

Route::delete("/withdraw/delete/{id?}",[CashoutController::class, "cash_delete"])->middleware("api_auth","email_verification","is_admin");