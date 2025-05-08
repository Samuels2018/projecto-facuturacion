<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaEmpresa extends Model
{
 
    protected $table = 'sistema_empresa';

    protected $connection = 'licencias';

   
    protected $primaryKey = 'rowid';

    
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'nombre_comercial',
        'direccion_fk_provincia',
        'direccion_fk_municipio',
        'telefono_fijo',
        'telefono_movil',
        'website',
        'fk_estado',
        'creado_fecha',
        'creado_fk_usuario',
        'borrado',
        'borrado_fecha',
        'borrado_fk_usuario',
        'fk_sistema_empresa_licencias',
        'company_externo',
        'avatar',
        'kit_aplica_kit_digital',
        'kit_fk_tipo',
        'kit_pdf_firmado',
        'kit_pdf_firmado_url_en_disco',
        'kit_direccion_completa',
        'kit_codigo_postal',
        'kit_factura_emitida',
        'kit_factura_emitida_fecha',
        'kit_factura_emitida_pagada',
        'kit_monto_aprobado',
        'kit_monto_comision',
        'kit_monto_comision_pagada',
        'vendedor_fk_usuario',
        'tipo',
        'cedula',
        'notas_empresa',
        'fk_kit_digital_estado',
        'verifactum_produccion',
        'verifactum_produccion_fecha',
    ];

    // Definimos los atributos que deben ser tratados como fechas
    protected $dates = [
        'creado_fecha',
        'borrado_fecha',
        'kit_factura_emitida_fecha',
        'verifactum_produccion_fecha',
    ];

    // Relación con la tabla `sistema_empresa_licencias` (si existe)
    // public function licencia()
    // {
    //     return $this->belongsTo(SistemaEmpresaLicencia::class, 'fk_sistema_empresa_licencias');
    // }

    // // Relación con el `Usuario` que creó la empresa
    // public function creador()
    // {
    //     return $this->belongsTo(Usuario::class, 'creado_fk_usuario');
    // }

    // // Relación con el `Usuario` que borró la empresa
    // public function borrador()
    // {
    //     return $this->belongsTo(Usuario::class, 'borrado_fk_usuario');
    // }

    // // Relación con el `Usuario` vendedor
    // public function vendedor()
    // {
    //     return $this->belongsTo(Usuario::class, 'vendedor_fk_usuario');
    // }

    // // Relación con el estado de la empresa
    // public function estado()
    // {
    //     return $this->belongsTo(Estado::class, 'fk_estado');
    // }

    // // Relación con el estado del Kit digital
    // public function estadoKitDigital()
    // {
    //     return $this->belongsTo(KitDigitalEstado::class, 'fk_kit_digital_estado');
    // }

    // // Relación con la provincia de la empresa
    // public function provincia()
    // {
    //     return $this->belongsTo(Provincia::class, 'direccion_fk_provincia');
    // }

    // // Relación con el municipio de la empresa
    // public function municipio()
    // {
    //     return $this->belongsTo(Municipio::class, 'direccion_fk_municipio');
    // }
}
