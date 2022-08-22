<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Transference;
use App\Models\Wallet;
use App\Models\User;

use App\Http\Requests\DepositValidator;
use Illuminate\Support\Facades\Crypt;

class TransferController extends Controller
{
    public function index (DepositValidator $request) {

        //checking if the amount is greater than 0
        if($request->amount <= 0){
            return response()->json(["error" => "Montante invalido"]);
        }

        if($request->from == $request->to){
            return response()->json(["error" => "Codigo de carteira do destino invalido"]);
        }

        //getting user details
        $wallet_user_login = Wallet::where("wallet_id",$request->from)
                                    ->where("user_id",base64_decode(Crypt::decrypt($request->header("access_token"))))->first();
        
        //checking if the user exists
        if(!$wallet_user_login){
            return response()->json(["error" => "Codigo de carteira invalido"]);
        }

        //checking if the destination exists
        $wallet_user_receiver = Wallet::where("wallet_id",$request->to)->first();

        if(!$wallet_user_receiver){
            return response()->json(["error" => "Codigo de carteira do destino invalido"]);
        }

        //checking if the money is available
        if($wallet_user_login->wallet_money < $request->amount){
            return response()->json(["error" => "Saldo insuficiente"]);
        }

        //generating new key

        $new_reference = rand(100000,999999);
        //insert into transference
        $tranfer = Transference::create([
            "sent_amount" => $request->amount,
            "sent_from_wallet_id" => $request->from,
            "sent_to_wallet_id" => $request->to,
            "sent_reference" => $new_reference
        ]);

        //updating users wallet
        $sender_money = $wallet_user_login->wallet_money - floatval($request->amount);
        $wallet_user_login->update(["wallet_money" => $sender_money]);

        $receiver_money = $wallet_user_receiver->wallet_money + floatval($request->amount);
        $wallet_user_receiver->update(["wallet_money" => $receiver_money ]);

        return response()->json(["success" => "Transferido: ".$request->amount."Mt para: ".$request->to ]);
    }

    public function all_transferences() {

        $sents = Transference::orderBy("sent_at","desc")->paginate(10);

        if(!$sents){
            return response()->json(["message" => "Vazio"]);
        }

        return $sents;
    }

    public function transference_by_id($id = 0 ) {

        $sent = Transference::find($id);

        if(!$sent){
            return response()->json(["message" => "vazio"]);
        }

        return $sent;
    }

    public function transference_by_wallet_id($wallet_id = 0){

        $transfers = Transference::where("sent_from_wallet_id", $wallet_id)
                                ->orWhere("sent_to_wallet_id", $wallet_id)
                                ->orderBy("sent_at","desc")
                                ->paginate(10);

        $wallet = Wallet::where("wallet_id",$wallet_id)
                                ->first();
        
        if(!$transfers){
            return response()->json(["message" => "Vazio"]);
        }

        return ["wallet" => $wallet, "transfers" => $transfers];
    }
    

    public function transference_update($id) {}

    public function transference_delete($id = 0) {

        $delete = Transference::find($id);

        if(!$delete){
            return response()->json(["error" => "Transicao nao existe"]);
        }

        $delete->delete();

        return response()->json(["message" => "apagado com sucesso"]);
    }

   
    
}
