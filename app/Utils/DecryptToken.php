<?php

    namespace App\Utils;

    class DecryptToken {

        public function decrypt_token($token){
            $id = base64_decode(Crypt::decrypt($token));

            return $id;
        }
    }