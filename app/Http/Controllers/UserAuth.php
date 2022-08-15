<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserValidator;
use App\Http\Requests\PasswordValidator;
use App\Http\Requests\UserBasicValidator;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


class UserAuth extends Controller
{
    //

    public function index(UserValidator $request){
        
        //generating a custom user_id

        $request->user_id = rand(100000000,999999999);

        //finding if there is  user with the same phone number or email

        $user = User::where("user_email",$request->user_email)
                ->orWhere("user_phone_number",'258'.$request->user_phone_number)
                ->first();
        
        //returning an error if the user already exits
        if($user){
            return response()->json(["error" => "Usuario ja existe."]);
        }
        //inserting if there is no user with those credentials
        $response = User::create([
            "user_name" => $request->user_name,
            "user_email" => $request->user_email,
            "user_password" => Hash::make($request->user_password),
            "user_phone_number" => '258'.$request->user_phone_number,
            "user_birthdate" => $request->user_birthdate,
            "user_birthplace" => $request->user_birthplace,
            "user_id" => $request->user_id,
            "token" => $request->user_id,
            "pro_account" => 5
        ]);
        

        //returning the success message if there are no errors
        return response()->json(["success" => "usuario criado com sucesso."]);
    }

    public function signIn(Request $request){

        //checking if the user exists

        $user = User::where("user_email",$request->login)
                                    ->orWhere("user_phone_number",'258'.$request->login)
                                    ->first();
        
        if($user){
            //compare password hashes if the user exists
            if(!Hash::check($request->password, $user->user_password)){
                return response()->json(["error" => "senha incorreta"]);
            }
        }else{
            //return an error if the user doesn't exist
            return response()->json(["error" => "email ou celular invalido"]);
        }
        

        //generate token

        //hash the user_id using base64 then hash the base64 using crypt then concatenate with the user_id that is already encrypted

        $token = Crypt::encrypt(base64_encode($user->user_id));

        $user->where("user_id",$user->user_id)->update(["token" => $token]);
       

        //return the user token if logged successfully
        return response()->json(["token" => $token]);
    }

    public function resetPassword($email){}

    public function verifyEmail($email){}

    public function users () {

        $users = User::orderBy("user_created_at","desc")->paginate(10);

        if(!$users){
            return response()->json(["message" => "Vazio"]);
        }

        return $users;
    }

    public function user($id = 0) {

        $user = User::where("user_id", $id)
                        ->first();
        
        if(!$user){
            return response()->json(["message" => "Vazio"]);
        }

        return $user;
    }

    public function user_me(Request $request) {

        $user = User::where("user_id", base64_decode(Crypt::decrypt($request->header("access_token"))))
                        ->first();
        
        if(!$user){
            return resonse()->json(["message" => "Vazio"]);
        }

        return $user;
    }

    public function logout(Request $request){

        $id = base64_decode(Crypt::decrypt($request->header("access_token")));

        $logout = User::where("user_id",$id)
                        ->first();
        
        if(!$logout){
            return respose()->json(["error" => "Houve um erro"]);
        }

        $logout->where("user_id",$id)->update(["token" => null]);

        return response()->json(["success"=>"Sucesso"]);
    }

    public function update_password(PasswordValidator $request){
        
        //decrypting token to get user_id
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));

        //getting user info
        $user = User::where("user_id",$user_id)
                        ->first();
        //checking if user exists
        if(!$user){
            return response()->json(["error" => "Houve um erro"]);
        }

        //comparing user input password with his really password
        if(!Hash::check($request->user_password, $user->user_password)){
            return response()->json(["error" => "Senha incorreta"]);
        }

        //updating user password if no errors occurs
        $user->where("user_id",$user_id)->update(["user_password" => Hash::make($request->user_new_password)]);

        return response()->json(["success" => "Sucesso"]);
    }

    public function user_delete($id = 0) {
        
        //finding  the user
        $user = User::where("user_id",$id)->first();

        //checking if exists
        if(!$user){
            return response()->json(["error" => "O usuario nao existe"]);
        }

        //deleting in case exists
        $user->where("user_id",$id)->delete();

        return response()->json(["success" => "Sucesso"]);
    }

    public function update_profile_photo(Request $request){}

    public function user_update(UserBasicValidator $request, $id = 0){

        //decryting user id from token
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));

        //getting user info
        $user = User::where("user_id",$user_id)
                        ->first();
        
        //checking if he wants to updating his email
        if($user->user_email != $request->user_email){
            //verifying if the new email is not taken yet
            $check_email = User::where("user_email",$request->user_email)->first();
            //returning an error if the email is taken
            if($check_email){
                return response()->json(["error" => "Alguem ja esta usando este email"]);
            }
        }

        //checking if he wants to update his mobile phone number
        if($user->user_phone_number != $request->user_phone_number_){
            //checking if the new mobile phone is available
            $check_phone = User::where("user_phone_number",'258'.$request->user_phone_number)->first();
            //returning an error if the phone is taken yet
            if($check_phone){
                return response()->json(["error" => "Alguem ja esta usando este numero"]);
            }
        }

        //updating to the new data
        $user->where("user_id",$user_id)->update([
            "user_name" => $request->user_name,
            "user_email" => $request->user_email,
            "user_phone_number" => '258'.$request->user_phone_number
        ]);
        //returning success message
        return response()->json(["success" => "Sucesso"]);

    }



}
