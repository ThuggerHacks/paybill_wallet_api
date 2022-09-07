<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Requests\PaymentValidator;
use App\Http\Requests\WalletPayValidator;


use App\Models\Payment;
use App\Models\Wallet;
use App\Models\User;

use Illuminate\Support\Facades\Crypt;

class PaymentController extends Controller
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

    private function is_decimal( $val ){
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    //payment via mpesa
    public function index(PaymentValidator $request,$wallet_id = 0){

        //decrypting secret t find user_id
        $user_id = base64_decode(Crypt::decrypt($request->secret_key));

        //check if the amount is valid

        if( $request->payment_amount <= 0 || !is_numeric($request->payment_amount)) {

            return response()->json(["error" => "Invalid amount"]);
        }
        //check if user is available
        
        $user = User::where("user_id",$user_id)->first();

        if(!$user){
            return response()->json(["error" => "Invalid credentials"]);
        }

        //checking if wallet is available

        $wallet = Wallet::where("wallet_id",$wallet_id)->where("user_id",$user_id)->first();
        
        if(!$wallet){
            return response()->json(["error" => "Invalid wallet id"]);
        }

        //if everything is okay, now can pay
        //sending money to mpesa before sending to user wallet

        $sent = $this->sendC2bRequest($request->payment_amount,$request->payer_wallet_id);

        //check if has sent
        if(!$sent){
            return response()->json(["error" => "Houve um erro, volte a tentar mais tarde."]);  
        }

        $tax = ($request->payment_amount*config("constants.tax_amount_payment")/100);

        //pay tax
        $new_reference = rand(100000000,999999999);

        $wallet_tax = Wallet::where("wallet_id",config("constants.tax_wallet_id"))->first();

        if(!$wallet_tax){
            return response()->json(["error" => "Houve um erro, volte a tentar mais tarde."]); 
        }


        $payment = Payment::create([
            "payment_reference" => $new_reference,
            "payment_title" => $wallet_tax->wallet_title,
            "payment_amount" =>  $tax,
            "wallet_id" => $wallet_tax->wallet_id,
            "payer_wallet_id" => $wallet->wallet_id
         ]);

         //insert into the PAYMENTS
         $new_reference = rand(100000000,999999999);

         $payment = Payment::create([
            "payment_reference" => $new_reference,
            "payment_title" => $wallet->wallet_title,
            "payment_amount" => ($request->payment_amount - $tax),
            "wallet_id" => $wallet->wallet_id,
            "payer_wallet_id" => $request->payer_wallet_id
         ]);


         
          //updating wallet

    
       
        $new_amount = ($wallet->wallet_money + (floatval($request->payment_amount)-$tax));

        $wallet->update(["wallet_money" => $new_amount ]);

        return response()->json(["success" => "Payment proceeded successfully"]);
        
    }

    //payment via wallet_id

    public function pay_with_wallet(WalletPayValidator $request,$wallet_id = 0){
        
        try{
             //decrypting secret t find user_id
            $user_id = base64_decode(Crypt::decrypt($request->secret_key));
         
            //getting access token
            $client_id = base64_decode(Crypt::decrypt($request->header("access_token")));

            //verifying if user/seller  data is valid
            $user = User::where("user_id",$user_id)->first();

            if( $request->payment_amount <= 0 || !is_numeric($request->payment_amount)) {

                return response()->json(["error" => "Montante invalido"]);
            }

            if(!$user){
                return response()->json(["error" => "Houve um erro, por favor volte a tentar mais tarde"]);
            }

            //checking if wallet is available

            $wallet = Wallet::where("wallet_id",$wallet_id)->where("user_id",$user_id)->first();
                
            if(!$wallet){
                return response()->json(["error" => "Carteira do vendedor invalida"]);
            }

            //verifying if client data is valid

            $client = User::where("user_id",$client_id)->first();

            if(!$client){
                return response()->json(["error" => "Houve um erro, por favor volte a tentar mais tarde"]);
            }
            

            $wallet_client = Wallet::where("wallet_id",$request->payer_wallet_id)->where("user_id", $client_id)->first();

            if(!$wallet_client){
                return response()->json(["error" => "Carteira do comprador invalida"]);
            }

            if($wallet_client->wallet_money < ( $request->payment_amount + ($request->payment_amount*config("constants.tax_amount_payment")/100))){
                return response()->json(["error" => "Saldo insuficiente"]);
            }

            //if everything is okay, now can pay

                //insert into the PAYMENTS
                $new_reference = rand(100000000,999999999);

                $payment = Payment::create([
                "payment_reference" => $new_reference,
                "payment_title" => $wallet->wallet_title,
                "payment_amount" => $request->payment_amount,
                "wallet_id" => $wallet->wallet_id,
                "payer_wallet_id" => $request->payer_wallet_id
                ]);


                
                //updating seller wallet

            $new_amount = ($wallet->wallet_money + floatval($request->payment_amount));

            $wallet->update(["wallet_money" => $new_amount ]);

            //updating client wallet

                $new_amount_client = ($wallet_client->wallet_money - floatval($request->payment_amount));

            $wallet_client->update(["wallet_money" => $new_amount_client ]);

            return response()->json(["success" => "Pagamento bem sucedido"]);
        }catch(Exception $ex){
             return response()->json(["error" => "Houve um erro, por favor volte a tentar mais tarde"]);
        }


    }

    public function get_payment_by_wallet_id(Request $request,$wallet_id = 0){

        $user_id = base64_decode(Crypt::decrypt($request->header("access_token"))); 

        $user = User::where("user_id",$user_id)->first();

        if(!$user){
            return response()->json(["error" => "Houve um erro, por favor volte a tentar mais tarde"]);
        }

        $wallet = Wallet::where("wallet_id",$wallet_id)->where("user_id",$user_id)->first();

        if(!$wallet){
            return response()->json(["error" => "Carteira nao existe"]);
        }

        $payment = Payment::where("payer_wallet_id",$wallet_id)->orWhere("wallet_id",$wallet_id)->orderBy("payment_date","desc")->paginate(10);

        return ["wallet" => $wallet,"payments" => $payment];


    }

    public function get_payment_by_id(Request $request, $id = 0){

        $payment = Payment::find($id);

        if(!$payment){
            return response()->json(["error" => "Pagamento nao existe"]);
        }
        $wallet = Wallet::where("wallet_id",$payment->wallet_id)->first();
        return ["payments" => $payment, "wallet" => $wallet];
    }

    public function get_payments(){

        $payments = Payment::orderBy("payment_date","desc")->paginate(10);

        if(!$payments){
            return response()->json(["error" => "vazio"]);
        }

        return $payments;
    }
    

    
}
