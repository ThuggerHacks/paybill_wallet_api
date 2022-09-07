<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PricingValidator;

use App\Models\Pricing;
use App\Models\User;
use App\Models\Wallet;

use Illuminate\Support\Facades\Crypt;

class PricingController extends Controller
{

    public function index(){

        $cards = Pricing::get();

        return ["data" =>  $cards];
    }

    public function create(PricingValidator $request){

        $title = $request->pricing_title;
        $amount = $request->pricing_amount;
        $accounts = $request->accounts_number;

        $pricing = Pricing::where("pricing_title",$title)->where("pricing_amount",$amount)->where("accounts_number",$accounts)->first();

        if($pricing){
            return ["error" => "Cartao ja existe"];
        }

        $pricing = Pricing::create([
            "pricing_title" => $title,
            "pricing_amount" => $amount,
            "accounts_number" => $accounts
        ]);

        return ["success" => "Inserido com sucesso"];


    }

    public function remove( $card_id = 0 ){

        $card = Pricing::find($card_id);

        if(!$card){
            return ["error" => "Cartao nao existe"];
        }

        $card->delete();

        return ["success" => "Cartao eliminado com sucesso"];
    }

    public function update(PricingValidator $request, $card_id = 0){

        $card = Pricing::find($card_id);

        if(!$card){
            return ["error" => "Cartao nao existe"];
        }

        $card->update($request->all());

        return ["success" => "Cartao atualizado com sucesso"];
    }

    public function upgradeAccount(Request $request){

        $wallet = Wallet::where("wallet_id",$request->wallet_id)->first();

        //checking if the wallet exist
        if(!$wallet){
            return ["error" => "Carteira invalida"];
        }

        //checking if the card exists
        $card = Pricing::find($request->card_id);

        if(!$card){
            return ["error" => "Pacote invalido"];
        }

        $user = base64_decode(Crypt::decrypt($request->header("access_token")));

        //get current user

        $userData = User::where("user_id",$user)->first();
        //checking if user exists
        if(!$userData){
            return ["error" => "Conta de usuario invalida"];
        }
        //updating user pro account
        $userData->where("user_id",$user)->update([
            "pro_account" => ($userData->pro_account + $card->accounts_number)
        ]);


       return ["success" => "Atualizado com sucesso, agora podes ter ate: ".($userData->pro_account + $card->accounts_number)." contas" ];

    }

    public function cardById($id = 0){

        $card = Pricing::find($id);

        if(!$card){
            return ["error" => "O cartao nao existe"];
        }

        return ["data" => $card];
    }
}
