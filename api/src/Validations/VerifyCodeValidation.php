<?php 

namespace App\Validations;

use App\Interfaces\ValidateInterface;
use App\Validations\BaseValidation;

class VerifyCodeValidation extends BaseValidation  implements ValidateInterface{

    public function rules(){
        return [
            'code_1' => 'required',
            'code_1' => 'max:1',
            'code_1' => 'min:1',
            'code_1' => 'numeric',

            'code_2' => 'required',
            'code_2' => 'max:1',
            'code_2' => 'min:1',
            'code_2' => 'numeric',

            'code_3' => 'required',
            'code_3' => 'max:1',
            'code_3' => 'min:1',
            'code_3' => 'numeric',


            'code_4' => 'required',
            'code_4' => 'max:1',
            'code_4' => 'min:1',
            'code_4' => 'numeric'


        ];
    }
}
