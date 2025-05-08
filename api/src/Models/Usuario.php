<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $connection = 'licencias';

    // Especificamos el nombre de la tabla
    protected $table = 'usuarios';

    // Especificamos la clave primaria
    protected $primaryKey = 'rowid';

    // Especificamos si la clave primaria es autoincremental (por defecto, true)
    public $incrementing = true;

    // Definimos si las marcas de tiempo (created_at, updated_at) están desactivadas
    public $timestamps = false;

    // Asignación masiva permitida para los campos especificados
    protected $fillable = [
        'nombre',
        'apellidos',
        'acceso_usuario',
        'acceso_clave',
        'acceso_correo_estado',
        'acceso_correo_actualizado',
        'acceso_correo_codigo',
        'acceso_correo_actualizado_validado',
        'usuario_avatar',
        'usuario_telefono',
        'fk_estado',
        'fk_idioma',
        'fk_provincia',
        'correo_temporal',
        'creacion_estado'
    ];

    // Definimos los atributos que deben ser tratados como fechas
    protected $dates = [
        'acceso_correo_actualizado',
        'acceso_correo_actualizado_validado',
    ];

    // Aquí puedes definir las relaciones, si existen
    // Ejemplo de relación: un usuario pertenece a un estado
    // public function estado()
    // {
    //     return $this->belongsTo(Estado::class, 'fk_estado');
    // }

    // public function idioma()
    // {
    //     return $this->belongsTo(Idioma::class, 'fk_idioma');
    // }

    // public function provincia()
    // {
    //     return $this->belongsTo(Provincia::class, 'fk_provincia');
    // }
}

