<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;


Route::get("/cards",[PricingController::class,"index"])->middleware("api_auth","email_verification");

Route::post("/cards/create",[PricingController::class,"create"])->middleware("api_auth","email_verification","is_admin");

Route::delete("/cards/delete/{card_id?}",[PricingController::class,"remove"])->middleware("api_auth","email_verification","is_admin");

Route::put("/cards/update/{card_id?}",[PricingController::class,"update"])->middleware("api_auth","email_verification","is_admin");

Route::put("/cards/update/pro/{wallet_id?}/{card_id?}",[PricingController::class,"upgradeAccount"])->middleware("api_auth","email_verification");