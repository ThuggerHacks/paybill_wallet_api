<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Deposit;
use App\Models\Wallet;
use App\Models\User;

use App\Http\Requests\DepositValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class DepositController extends Controller
{
    
    private function sendC2bRequest($amount,$from){

        //send the money to mpesa from customer to the owner of the bank wallet
        try{
            $response = Http::post(config("constants.mpesa_endpoint")."/c2b",[
                "amount" => $amount,
                "msisdn" => $from, 
                "transaction_ref" => "T12344C", 
                "thirdparty_ref" => "11114"
            ]);

            if(isset($response['error'])){
                return false;
            }
            //checking if the transaction was successfully done.
            $check_transation = $response['output_ResponseDesc'] == "Request processed successfully"?true:false;

            return  $check_transation;
        }catch( Exception $e){
            return false;
        }
    }


    public function index (DepositValidator $request) {
        
        //amount gotta be greather than 0
        if($request->amount <= 0 ) {
            return response()->json(["error" => "montante invalido"]);
        }

        //checking if the wallet exists for deposit

        $wallet = Wallet::where("wallet_id",$request->to)
                        ->where("user_id",base64_decode(Crypt::decrypt($request->header("access_token"))))
                        ->first();

        //return error if wallet doesn exist
        if(!$wallet){
            return response()->json(["error" => "O codigo de carteira eh invalido"]);
        }

        //sending money to mpesa before sending to user wallet

        $sent = $this->sendC2bRequest($request->amount,$request->from);

        //check if has sent
        if(!$sent){
            return response()->json(["error" => "Houve um erro, volte a tentar mais tarde."]);  
        }


        //insert into the deposits
        $new_reference = rand(100000000,999999999);

        $deposit = Deposit::create([
            "deposit_amount" => $request->amount,
            "deposit_from" => $request->from,
            "deposit_to_wallet_id" => $request->to,
            "deposit_reference" => $new_reference
        ]);

        //updating wallet

        $new_amount = ($wallet->wallet_money + floatval($request->amount));

        $wallet->update(["wallet_money" => $new_amount ]);

        return response()->json(["success" => "Depositado com sucesso ".$request->amount."Mt na conta: ".$request->to]);
        
    }

    public function deposit_all() {

        $deposits = Deposit::orderBy("deposited_at","desc")->paginate(10);

        if(!$deposits) {
            return response()->json(["message" => "vazio"]);
        }

        return $deposits;
    }

    public function deposit_by_id($id = 0) {

        $deposit = Deposit::find($id);

        if(!$deposit){
            return response()->json(["error" => "vazio"]);
        }
        $wallet = Wallet::find($deposit->deposit_to_wallet_id);
        
        return ["deposit" => $deposit, "wallet" => $wallet];
    }

    public function deposit_by_wallet_id($wallet_id = 0) {

        $deposits = Deposit::where("deposit_to_wallet_id",$wallet_id)
                            ->orderBy("deposited_at","desc")
                            ->paginate(10);
        $wallet = Wallet::where("wallet_id",$wallet_id)
                            ->first();
        
        if(!$deposits){
            return response()->json(["message" => "Vazio"]);
        }

        return ["wallet" => $wallet, "deposits" => $deposits];
    }

    public function deposit_update($id = 0) {}

    public function deposit_delete($id = 0) {

        $deposit = Deposit::find($id);

        if(!$deposit){
            return response()->json(["error" => "O deposito nao existe"]);
        }

        $deposit->delete();

        return response()->json(["success" => "apagado com sucesso"]);
        
    }
}
