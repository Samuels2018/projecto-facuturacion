<?php 

namespace App\Validations;

use App\Core\Request;

class BaseValidation {

    public $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function run_validations(){

        $request = new Request();
        $errors = $request->validate($this->rules());

        $message = [];

        if (!empty($errors)) {
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $message[] = $error;
                }
            
            } 
        }

        if($message) throw new \Exception(json_encode($message), 422);
        
    }

    public function inputs(){

        return $this->request->all();
    }

    public function get($key){

        return $this->request->get($key);
    }

    
    public function rules(){
        return [];
    }

    
}