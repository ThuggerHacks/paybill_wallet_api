<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

//validators
use App\Http\Requests\WalletValidator;
use App\Http\Requests\PhoneValidator;

//models
use App\Models\Wallet;
use App\Models\User;

use Illuminate\Support\Facades\Crypt;


class WalletController extends Controller
{
    //

    public function index(WalletValidator $request) {

        //getting the user id by the token
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));
        

        $user = User::where("user_id",$user_id)->first();
        

        //if he has not a pro account, just can create maximum 5 wallets  
        $wallets = Wallet::where("user_id",$user_id)->get();

        if(count($wallets) >= $user->pro_account ) {
            return response()->json(["error" => "Ja tem mais de ". $user->pro_account." carteiras, atualiza-se para pro para criar mais carteiras"]);
        }

        //generating a wallet id
        $new_wallet_id = rand(10000000000,99999999999);

        //checking if the title already exists in this user's wallet collection
        $check_title = Wallet::where("wallet_title",$request->wallet_title)
                                ->where("user_id",$user_id)->first();
        
        if($check_title){
            return response()->json(["error" => "Ja tens uma carteira com este titulo"]);
        }

        //creating a new wallet
        $new_wallet = Wallet::create([
            "user_id" => $user_id,
            "wallet_title" => $request->wallet_title,
            "wallet_associated_phone_number" => '258'.$request->wallet_associated_phone_number,
            "wallet_id" => $new_wallet_id
        ]);

        //return wallet details after criation
        return response()->json(["wallet_id" => $new_wallet_id, "wallet_associated_phone_number" => '258'.$request->wallet_associated_phone_number]);
        
     
    }

    public function wallet_all() {

        $wallets = Wallet::orderBy("wallet_created_at","desc")->paginate(10);

        if(!$wallets){
            return response()->json(["mensagem" => "nao tem carteiras"]);
        }

        return $wallets;
    }

    public function wallet_by_id(Request $request, $wallet_id = 0) {

        //decrypting token to get the user id
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));

        //finding the logged user wallets
        $wallet = Wallet::where("wallet_id",$wallet_id)
                        ->where("user_id",$user_id)
                        ->first();
        //return a message if there are no wallets of the user
        if(!$wallet){
            return response()->json(["message" => "Carteira nao existe"]);
        }

        //returning the wallet data
        return $wallet;
    }

    public function wallet_user_id(Request $request){
        
        $user_wallets = Wallet::where("user_id",base64_decode(Crypt::decrypt($request->header("access_token"))))->orderBy("wallet_created_at", "desc")->paginate(10);

        if(!$user_wallets){
            return response()->json(["error" => "Vazio"]);
        }

        return $user_wallets;
    }

    public function update_wallet(Request $request, $id = 0) {

        if(trim($request->wallet_title) == ''){

            return response()->json(["error" => "Por favor digite o novo titulo"]);
        }else if( strlen($request->wallet_title) < 3 ) {
            return response()->json(["error" => "Titulo deve ter pelomenos 3 digitos"]);
        }else if( strlen($request->wallet_title) > 20){
            return response()->json(["error" => "Titulo deve ter ate 20 digitos"]);
        }

        //decrypting user token to get id
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));

        //checking if the wallet exists
        $wallet = Wallet::where("user_id",$user_id)
                        ->where("wallet_id",$id)
                        ->first();
        if(!$wallet){
            return response()->json(["error" => "Carteira nao existe"]);
        }

         //checking if the title already exists in this user's wallet collection
         $check_title =  $wallet->where("wallet_title",$request->wallet_title)
         ->where("user_id",$user_id)->first();

        if($check_title){
            return response()->json(["error" => "Ja tens uma carteira com este titulo"]);
        }

        $wallet->update(["wallet_title" => $request->wallet_title]);

        return $wallet;
    }

    public function update_associated_phone_number(PhoneValidator $request,$id = 0){

        //getting phonenumber
        $phone = $request->wallet_associated_phone_number;

        //check the wallet if belongs to the user
        $update_phone = Wallet::where("wallet_id",$id)
                                ->where("user_id",base64_decode(Crypt::decrypt($request->header("access_token"))))
                                ->first();
        
        if(!$update_phone){
            return response()->json(["message" => "Houve um erro"]);
        }

        //check if he is updating for a new number or not
        if('258'.$phone == $update_phone->wallet_associated_phone_number){
            return response()->json(["error" => "Impossivel atualizar para o mesmo numero em uso"]);
        }

        //updating
        $update_phone->update(["wallet_associated_phone_number" => '258'.$phone]);

        return response()->json(["success" => "Atualizado para: ".$phone]);
    }

    public function delete_wallet(Request $request,$id = 0){
        
         //getting the user id by the token
        $user_id = base64_decode(Crypt::decrypt($request->header("access_token")));

        $wallet = Wallet::where("user_id",$user_id)
                            ->where("wallet_id",$id)
                            ->first();
        
        if(!$wallet){
            return response()->json(["error" => "A carteira nao existe"]);
        }

        if($wallet->wallet_money > 0){
            return response()->json(["error" => "impossivel apagar esta carteira, ja existem transacoes nela"]);
        }
        $wallet->delete();

        return response()->json(["message" => "apagada com sucesso"]);
         
    }
    
}
