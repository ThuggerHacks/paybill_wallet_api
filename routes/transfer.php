<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController;

Route::post("/transfer",[TransferController::class, "index"])->middleware("api_auth","email_verification");

Route::get("/transfer/all",[TransferController::class, "all_transferences"])->middleware("api_auth","email_verification","is_admin");

Route::get("/transfer/{id?}",[TransferController::class, "transference_by_id"])->middleware("api_auth","email_verification");

Route::get("/transfer/wallet/{wallet_id?}",[TransferController::class, "transference_by_wallet_id"])->middleware("api_auth","email_verification");

//Route::put("/transfer/update/{id?}",[TransferController::class, "transference_update"])->middleware("api_auth","email_verification");

Route::delete("/transfer/delete/{id?}",[TransferController::class, "transference_delete"])->middleware("api_auth","email_verification");