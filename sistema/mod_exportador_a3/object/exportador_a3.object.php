<?php
class A3ProveedorLine
{
    public $codigo; // Ej: C43000610
    public $nombre;
    public $importe = '+0000000000.00';
    public $nif;
    public $direccion;
    public $poblacion;
    public $cp;
    public $provincia;
    public $delegacion;
    public $cuentaContable;
    public $tipoOperacion;

    public function buildLine()
    {
        return '59059220250103' .
            $this->fixLength($this->codigo, 10) .
            $this->fixLength($this->nombre, 35) .
            $this->fixLength($this->importe, 18) .
            $this->fixLength($this->nif, 9) .
            $this->fixLength($this->direccion, 40) .
            $this->fixLength($this->poblacion, 20) .
            $this->fixLength($this->cp, 5) .
            $this->fixLength($this->provincia, 15) .
            $this->fixLength($this->delegacion, 3) .
            str_repeat(' ', 63) .
            $this->fixLength($this->cuentaContable, 8) .
            str_repeat(' ', 6) .
            $this->fixLength($this->tipoOperacion, 2) .
            str_repeat(' ', 240) .
            'EN';
    }

    private function fixLength($text, $length)
    {
        return str_pad(substr($text, 0, $length), $length);
    }
}

class A3CabeceraFacturaLine
{
    public $codigoProveedor;
    public $numFactura;
    public $descripcion;
    public $correlativo;
    public $fecha; // formato DD-MM
    public $importeTotal;
    public $cp;
    public $fechaCompleta; // formato YYYYMMDD
    public $numFacturaExterna;

    public function buildLine()
    {
        return '59059220250103' .
            $this->fixLength($this->codigoProveedor, 10) .
            $this->fixLength($this->numFactura, 10) .
            $this->fixLength($this->descripcion, 20) .
            $this->fixLength($this->correlativo, 8) .
            $this->fixLength($this->fecha, 5) .
            $this->fixLength($this->importeTotal, 18) .
            str_repeat(' ', 100) .
            $this->fixLength($this->cp, 5) .
            str_repeat(' ', 10) .
            $this->fixLength($this->fechaCompleta, 8) .
            $this->fixLength($this->numFacturaExterna, 10) .
            str_repeat(' ', 190) .
            'EN';
    }

    private function fixLength($text, $length)
    {
        return str_pad(substr($text, 0, $length), $length);
    }
}

class A3DetalleFacturaLine
{
    public $codigoLinea;
    public $concepto;
    public $descripcion;
    public $numFactura;
    public $fecha;
    public $baseImponible;
    public $iva;
    public $cuotaIva;
    public $retencion = '+0000000000.00';
    public $recargoEq = '+0000000000.00';
    public $claveContable;
    public $cuentaContable;

    public function buildLine()
    {
        return '59059220250103' .
            $this->fixLength($this->codigoLinea, 10) .
            $this->fixLength($this->concepto, 30) .
            $this->fixLength($this->descripcion, 10) .
            $this->fixLength($this->numFactura, 10) .
            $this->fixLength($this->fecha, 7) .
            $this->fixLength($this->baseImponible, 18) .
            $this->fixLength($this->iva, 5) .
            $this->fixLength($this->cuotaIva, 18) .
            $this->fixLength($this->retencion, 18) .
            $this->fixLength($this->recargoEq, 18) .
            $this->fixLength('1', 1) .
            $this->fixLength($this->claveContable, 2) .
            str_repeat(' ', 51) .
            $this->fixLength($this->cuentaContable, 8) .
            str_repeat(' ', 141) .
            'EN';
    }

    private function fixLength($text, $length)
    {
        return str_pad(substr($text, 0, $length), $length);
    }
}

// Uso
$proveedor = new A3ProveedorLine();
$proveedor->codigo = 'C43000610';
$proveedor->nombre = 'DUERTE PANIAGUA LUCILA ELIZABEN';
$proveedor->nif = 'Y8535629K';
$proveedor->direccion = 'PG CURISCADA TRA. GENERAL';
$proveedor->poblacion = 'TINEO';
$proveedor->cp = '33870';
$proveedor->provincia = 'ASTURIAS';
$proveedor->delegacion = '011';
$proveedor->cuentaContable = '70500000';
$proveedor->tipoOperacion = '02';

$cabecera = new A3CabeceraFacturaLine();
$cabecera->codigoProveedor = 'C43000610';
$cabecera->numFactura = '100000001';
$cabecera->descripcion = 'IDUERTE PANIAGUA';
$cabecera->correlativo = '00000001';
cabecera->fecha = '03-01';
cabecera->importeTotal = '+0000000260.70';
cabecera->cp = '33870';
cabecera->fechaCompleta = '20250103';
cabecera->numFacturaExterna = '00000001';

$detalle = new A3DetalleFacturaLine();
$detalle->codigoLinea = '9705000000';
$detalle->concepto = 'PRESTACIONES DE SERVICIOS';
$detalle->descripcion = 'UDUERTE PA';
$detalle->numFactura = '00000001';
$detalle->fecha = '03-0101';
$detalle->baseImponible = '+0000000215.45';
$detalle->iva = '21.00';
$detalle->cuotaIva = '+0000000045.25';
$detalle->claveContable = 'SN';
$detalle->cuentaContable = '47700000';

// Escribir archivo
$fileContent = $proveedor->buildLine() . "\n";
$fileContent .= $cabecera->buildLine() . "\n";
$fileContent .= $detalle->buildLine() . "\n";

file_put_contents('a3_facturas.dat', $fileContent);

echo "Archivo a3_facturas.dat generado con éxito.";