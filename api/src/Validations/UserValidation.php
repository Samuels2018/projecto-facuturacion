<?php 

namespace App\Validations;

use App\Interfaces\ValidateInterface;
use App\Validations\BaseValidation;

class UserValidation  extends BaseValidation  implements ValidateInterface{

    public function rules(){
        return [
            'nombre' => 'required',
            'nombre' => 'max:255',
            'nombre' => 'min:3',
            'apellido' => 'required',
            'apellido' => 'max:255',
            'apellido' => 'min:3',
            'clave' => 'required',
            'clave' => 'max:255',
            'clave' => 'min:8',
            'correo' => 'email',
            'correo' => 'required',
            'correo' => 'max:255',
            'correo2' => 'email',
            'correo2' => 'required',
            'correo2' => 'max:255',
            'correo' => 'equals:correo2',
            'terminos_y_condiciones' => 'required',
        ];
    }
}
