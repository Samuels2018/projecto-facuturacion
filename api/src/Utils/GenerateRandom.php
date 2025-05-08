<?php 
    namespace App\Utils;


    trait GenerateRandom {
      
        public function random_code_email() {

            return random_int(1001, 9999);
       
        }

        public function generateRandomCode($length = 10) {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; 
            $charactersLength = strlen($characters);
            $randomCode = '';
        
            for ($i = 0; $i < $length; $i++) {
                $randomCode .= $characters[rand(0, $charactersLength - 1)]; 
            }
        
            return $randomCode;
        }
     
    }
    