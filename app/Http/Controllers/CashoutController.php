<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\WithDraw;
use App\Models\Wallet;
use App\Models\User;

use App\Http\Requests\CashOutValidator;
use Illuminate\Support\Facades\Crypt;


class CashoutController extends Controller
{
    private function sendB2cRequest($amount,$to){

        //send the money to mpesa from customer to the owner of the bank wallet
        try{
            $response = Http::post(config("constants.mpesa_endpoint")."/b2c",[
                "amount" => $amount,
                "msisdn" => $to, 
                "transaction_ref" => "T12344C", 
                "thirdparty_ref" => "11114"
            ]);

            //checking if the transaction was successfully done.
            $check_transation = $response['output_ResponseDesc'] == "Request processed successfully"?true:false;

            return  $check_transation;
        }catch( Exception $e){
            return false;
        }
    }

    public function index(CashOutValidator $request) {

         //amount gotta be greather than 0
         if($request->amount <= 0 ) {
            return response()->json(["error" => "montante invalido"]);
        }

        //checking if the wallet exists for deposit

        $wallet = Wallet::where("wallet_id",$request->from)
                        ->where("user_id",base64_decode(Crypt::decrypt($request->header("access_token"))))
                        ->first();
        

        //return error if wallet doesn exist
        if(!$wallet){
            return response()->json(["error" => "O codigo de carteira eh invalido"]);
        }

        //check if the money he wants to withdraw is available

        if($wallet->wallet_money < $request->amount){
            return response()->json(["error" => "Saldo indisponivel"]);
        }

        //sending money to mpesa before sending to user wallet

        $sent = $this->sendB2cRequest($request->amount,$wallet->wallet_associated_phone_number);

        //check if has sent
        if(!$sent){
            return response()->json(["error" => "Houve um erro, volte a tentar mais tarde."]);  
        }


        //insert into the withdraws
        $new_reference = rand(100000,999999);

        $cashout = WithDraw::create([
            "withdraw_amount" => $request->amount,
            "withdraw_wallet_id" => $request->from,
            "withdraw_reference" => $new_reference
        ]);

        //updating wallet

        $new_amount = ($wallet->wallet_money - floatval($request->amount));

        $wallet->update(["wallet_money" => $new_amount ]);

        return response()->json(["success" => "Levantado com sucesso ".$request->amount."Mt"]);

    }

    public function cash_all() {
        
        $cashouts = WithDraw::orderBy("withdraw_at","desc")->paginate(10);

        if(!$cashouts){
            return response()->json(["message" => "Vazio"]);
        }

        return $cashouts;

    }

    public function cash_by_id($id = 0) {

        $cashout = WithDraw::find($id);

        if(!$cashout){
            return response()->json(["error" => "Vazio"]);
        }

        return $cashout;
    }

    public function cash_by_wallet_id($wallet_id){

        $cashouts = WithDraw::where("withdraw_wallet_id",$wallet_id)
                                ->orderBy("withdraw_at","desc")
                                ->paginate(1);
        if(!$cashouts){
            return response()->json(["message" => "Vazio"]);
        }

        return $cashouts;
    }

    public function cash_update($id = 0) {}

    public function cash_delete($id = 0) {

        $delete = WithDraw::find($id);

        if(!$delete){
            return response()->json(["error" => "O levantamento nao existe"]);
        }

        $delete->delete();
        
        return response()->json(["success" => "Apagado com sucesso"]);
    }
}
