<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SavingController;

Route::post("/saving/make",[SavingController::class, "index"])->middleware("api_auth","email_verification");

Route::get("/saving/all",[SavingController::class, "saving_all"])->middleware("api_auth","email_verification","is_admin");

Route::get("/saving/{id?}",[SavingController::class, "saving_by_id"])->middleware("api_auth","email_verification");

Route::get("/saving/wallet/{wallet_id?}",[SavingController::class, "saving_by_wallet_id"])->middleware("api_auth","email_verification");

Route::put("/saving/update/{id?}",[SavingController::class, "saving_update"])->middleware("api_auth","email_verification");

Route::delete("/saving/delete/{id?}",[SavingController::class, "saving_delete"])->middleware("api_auth","email_verification");