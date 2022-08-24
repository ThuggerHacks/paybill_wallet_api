<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;


Route::post("/paybill/payment/provide/{wallet_id?}",[PaymentController::class,"index"]);

Route::post("/paybill/payment/wallet/{wallet_id?}",[PaymentController::class,"pay_with_wallet"])->middleware("api_auth","email_verification");

Route::get("/payments",[PaymentController::class,"get_payments"])->middleware("api_auth","email_verification","is_admin");

Route::get("/payments/{wallet_id?}",[PaymentController::class,"get_payment_by_wallet_id"])->middleware("api_auth","email_verification");

Route::get("/payment/{wallet_id?}",[PaymentController::class,"get_payment_by_id"])->middleware("api_auth","email_verification");

