<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth;


Route::post("/user/auth/sign-up",[UserAuth::class,"index"]);

Route::post("/user/auth/sign-in", [UserAuth::class,"signIn"]);

Route::post("/logout",[UserAuth::class,"logout"])->middleware("api_auth","email_verification");

Route::get("/user/all", [UserAuth::class,"users"])->middleware("api_auth","email_verification","is_admin");

Route::get("/user/profile", [UserAuth::class,"user_me"])->middleware("api_auth","email_verification");

Route::get("/user/{id?}", [UserAuth::class,"user"])->middleware("api_auth","email_verification","is_admin");

Route::put("/user/update/photo",[UserAuth::class,"update_profile_photo"])->middleware("api_auth","email_verification");

Route::put("/user/update/password",[UserAuth::class,"update_password"])->middleware("api_auth","email_verification");

Route::put("/user/update/{id?}", [UserAuth::class,"user_update"])->middleware("api_auth","email_verification");

Route::delete("/user/delete/{id?}", [UserAuth::class,"user_delete"])->middleware("api_auth","email_verification","is_admin");
