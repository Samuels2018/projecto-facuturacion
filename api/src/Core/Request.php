<?php
namespace App\Core;

class Request
{
    protected $data;

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
    }

    public function get($key, $default = '')
    {
        return $this->sanitizeInput($this->data[$key]) ?? $default;
    }

    protected function sanitizeInput($input) {
        
        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }


    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function validate(array $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if ($rule === 'required' && !$this->has($field)) {
                $errors[$field][] = "El campo $field es obligatorio";
            }

            if ($rule === 'email' && !filter_var($this->get($field), FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "El campos $field debe ser un correo válido";
            }

            if ($rule === 'numeric' && !is_numeric($this->get($field))) {
                $errors[$field][] = "El campo $field debe ser un número";
            }

            if (strpos($rule, 'length:') === 0) {
                $length = (int) substr($rule, 7); 
                if (strlen($this->get($field)) !== $length) {
                    $errors[$field][] = "El campo $field debe tener una longitud de $length caracteres";
                }
            }

            if (strpos($rule, 'min:') === 0) {
                $min = (int) substr($rule, 4); 
                if (strlen($this->get($field)) < $min) {
                    $errors[$field][] = "El campo $field debe tener al menos $min caracteres";
                }
                
            }

            if (strpos($rule, 'max:') === 0) {
                $max = (int) substr($rule, 4); 
                if (strlen($this->get($field)) > $max) {
                    $errors[$field][] = "El campo $field no debe exceder los $max caracteres";
                }
            }

            if (strpos($rule, 'equals:') === 0) {
                
                $field2 = str_replace("equals:", '' , $rule);
                
                $field1 = $this->get($field);
                $field2 = $this->get($field2);

                if ($field2 != $field1 ) {
                    $errors[$field][] = "El registro {$field1} es diferente a  {$field2} ";
                }
            }
        }

        return $errors;
    }
}