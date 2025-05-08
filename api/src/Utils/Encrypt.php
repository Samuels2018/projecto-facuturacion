<?php 
    namespace App\Utils;


    trait Encrypt {
      
        public function sha256_encrypt($password) {
            return hash('sha256' , hash('sha256' , hash('sha256', md5($password)  )));
        }
    }
    