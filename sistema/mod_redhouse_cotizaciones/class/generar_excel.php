<?php
// VALID DEFINIITON SESSION

if (!defined('ENLACE_WEB')) :
    session_start();
    require_once "../../conf/conf.php";
endif;


require_once ENLACE_SERVIDOR . 'mod_cotizaciones/object/cotizaciones.object.php';
require_once ENLACE_SERVIDOR . 'mod_terceros/object/terceros.object.php';
require_once ENLACE_SERVIDOR . "/mod_configuracion_agente/object/agente.object.php";
require_once ENLACE_SERVIDOR . 'mod_redhouse_cotizaciones/object/redhouse.cotizaciones.object.php';





$Factura = new redhouse_Cotizacion($dbh, $_SESSION['Entidad']);
$Factura->fetch($_REQUEST['id']);

$Tercero = new FiTerceros($dbh);
$Tercero->fetch($Factura->fk_tercero);

$agente = new Agente($dbh);
$agente_actual = $agente->obtener_agente_actual($Tercero->rowid);


$fecha_cotizacion = date("d/m/Y", strtotime($Factura->cotizacion_fecha));
$vigencia = ($Factura->cotizacion_validez_oferta == 0) ? "Sin Validez definida" : $Factura->cotizacion_validez_oferta . " Días";



//PHP SPREADSHEET para excel
// Incluir PhpSpreadsheet
include "../../include/spreadsheet/vendor/autoload.php";
		

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer ancho de columnas para mejor presentación
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);

// Título de la cotización
$sheet->setCellValue('A1', 'Información de la cotización:');
$sheet->mergeCells('A1:E1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1:E1')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFD9D9D9',
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);

// Define el estilo de borde
$styleArray = [
    'borders' => [
        'right' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Aplica el estilo de borde a la fila E (por ejemplo, de E1 a E10)
$sheet->getStyle('E1:E23')->applyFromArray($styleArray);
$sheet->getStyle('B2:B4')->applyFromArray($styleArray);



$styleArray = [
    'borders' => [
        'bottom' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];


// Aplica el estilo de borde a las celdas de la fila 23 en las columnas A, B, C y D
$sheet->getStyle('A23:D23')->applyFromArray($styleArray);
$sheet->getStyle('A2:B2')->applyFromArray($styleArray);
$sheet->getStyle('A3:B3')->applyFromArray($styleArray);
$sheet->getStyle('A4:B4')->applyFromArray($styleArray);
$sheet->getStyle('A7:E7')->applyFromArray($styleArray);
$sheet->getStyle('A10:E10')->applyFromArray($styleArray);
$sheet->getStyle('A11:E11')->applyFromArray($styleArray);
$sheet->getStyle('A12:E12')->applyFromArray($styleArray);
$sheet->getStyle('A13:E13')->applyFromArray($styleArray);
$sheet->getStyle('A14:E14')->applyFromArray($styleArray);

$path_imagen = ENLACE_SERVIDOR.'/bootstrap/img/redhouse-logopdf.png';

// Insertar la imagen a la derecha del título
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath($path_imagen); // Reemplaza con la ruta correcta de tu imagen
$drawing->setHeight(80); // Ajusta el tamaño de la imagen
$drawing->setCoordinates('C1');
$drawing->setOffsetX(0);
$drawing->setOffsetY(32);
$drawing->setWorksheet($sheet);

// Detalles de la cotización
// Define el estilo de fuente con color rojo
$spreadsheet->getActiveSheet()->getStyle('B2')
    ->getFont()
    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED));

// Luego, configura los valores en las celdas A2, B2, A3 y B3 como lo hiciste previamente
$sheet->setCellValue('A2', 'Consecutivo: ');
$sheet->setCellValue('B2', $Factura->cotizacion_referencia);
$sheet->setCellValue('A3', 'Fecha: ');
$sheet->setCellValue('B3', $fecha_cotizacion);
$sheet->setCellValue('A4', 'Vigencia: ');
$sheet->setCellValue('B4', $vigencia);

// Espacio
$sheet->setCellValue('A5', '');
$sheet->getStyle('A5')->getAlignment()->setWrapText(true); // Añade esta línea para permitir el salto de línea
$sheet->setCellValue('A5', "\n\n");

// Información del Cliente
$sheet->setCellValue('A6', 'Información del Cliente');
$sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A6:E6')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFD9D9D9',
        ],
    ],
    'borders' => [
        'top' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
        'bottom' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);
$sheet->setCellValue('A7', 'Nombre del Cliente: '.$Factura->nombre_cliente);

// Espacio
$sheet->setCellValue('A8', '');
$sheet->getStyle('A8')->getAlignment()->setWrapText(true); // Añade esta línea para permitir el salto de línea
$sheet->setCellValue('A8', "\n");

// Detalles del Evento
$sheet->setCellValue('A9', 'Detalles del Evento');
$sheet->getStyle('A9')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A9:E9')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFD9D9D9',
        ],
    ],
    'borders' => [
        'top' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
        'bottom' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);
$sheet->setCellValue('A10', 'Proyecto: '.$Factura->cotizacion_proyecto);
$sheet->setCellValue('A11', 'Lugar: '.$Factura->cotizacion_lugar_proyecto);
$sheet->setCellValue('A12', 'Fecha: '.date("d-m-Y",strtotime($Factura->cotizacion_fecha_proyecto)));
$sheet->setCellValue('A13', 'Hora: '.obtenerHoraConFormato($Factura->cotizacion_fecha_proyecto));

$sheet->setCellValue('A14', 'Otros: '.$Factura->cotizacion_descripcion_proyecto);

// Espacio
$sheet->setCellValue('A15', '');
$sheet->getStyle('A15')->getAlignment()->setWrapText(true); // Añade esta línea para permitir el salto de línea
$sheet->setCellValue('A15', "\n");

// Encabezado de la tabla de artículos
$sheet->setCellValue('A16', 'Descripción');
$sheet->setCellValue('B16', 'Duración');
$sheet->setCellValue('C16', 'Cantidad');
$sheet->setCellValue('D16', 'Subtotal');
$sheet->setCellValue('E16', 'Total');

$sheet->getStyle('A16:E16')->getFont()->setBold(true);
// Aplicar color de fondo y bordes al encabezado
$sheet->getStyle('A16:E16')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFD9D9D9',
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);



$sqldetalle  = "SELECT 
    f.*, 
    f.cantidad, 
    f.precio_subtotal, 
    p.label AS titulo_producto, 
    f.precio_tipo_impuesto, 
    f.fk_producto, 
    (SELECT label FROM fi_productos_imagenes WHERE fk_producto = p.rowid AND borrado = 0 ORDER BY rowid DESC LIMIT 1) AS imagen, 
    p.ref, 
    p.rowid AS id_producto,
    (SELECT SUM(f2.precio_subtotal) FROM a_medida_redhouse_cotizaciones_cotizaciones_servicios f2 WHERE f2.fk_cotizacion = f.fk_cotizacion) AS suma_precio_subtotal,
    (SELECT SUM(f2.precio_tipo_impuesto) FROM a_medida_redhouse_cotizaciones_cotizaciones_servicios f2 WHERE f2.fk_cotizacion = f.fk_cotizacion) AS suma_precio_tipo_impuesto,
    (SELECT SUM(f2.precio_total) FROM a_medida_redhouse_cotizaciones_cotizaciones_servicios f2 WHERE f2.fk_cotizacion = f.fk_cotizacion) AS suma_precio_total
FROM 
    a_medida_redhouse_cotizaciones_cotizaciones_servicios f 
LEFT JOIN 
    fi_productos p ON p.rowid = f.fk_producto 
WHERE 
    f.fk_cotizacion = ? 
GROUP BY 
    f.rowid;
";


$db = $dbh->prepare($sqldetalle);
$db->bindValue(1, $_REQUEST['id'], PDO::PARAM_INT);
$db->execute();
$suma_precio_subtotal = '';
$suma_precio_tipo_impuesto = '';
$suma_precio_total = '';



// Artículos
$startRow = 17;
while ($obj = $db->fetch(PDO::FETCH_ASSOC))
{
    $duracion = !empty($obj['tipo_duracion']) ? $obj['tipo_duracion'] : 'Días';


    $suma_precio_subtotal = numero_simple($obj['suma_precio_subtotal']);
    $suma_precio_tipo_impuesto = numero_simple($obj['suma_precio_tipo_impuesto']);
    $suma_precio_total = numero_simple($obj['suma_precio_total']);


	$sheet->setCellValue('A'.$startRow, $obj["titulo_producto"]);
	$sheet->setCellValue('B'.$startRow,intval($obj["cantidad_dias"]).' Días / '.$obj['tipo_duracion'].' Horas');
	$sheet->setCellValue('C'.$startRow,$obj["cantidad"]);
	$sheet->setCellValue('D'.$startRow, '₡ '.numero_simple($obj["precio_subtotal"]));
	$sheet->setCellValue('E'.$startRow, '₡ '.numero_simple($obj["precio_total"]));

	 // Aplicar bordes a la fila actual
    $sheet->getStyle('A' . $startRow . ':E' . $startRow)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);

    // Incrementar la fila para el próximo artículo
    $startRow++;

}

$startRow++;

// Subtotales y totales
$sheet->setCellValue('D'.$startRow, 'Subtotal');
$sheet->setCellValue('E'.$startRow, '₡ '.$suma_precio_subtotal);
$startRow++;
$sheet->setCellValue('D'.$startRow, 'IVA');
$sheet->setCellValue('E'.$startRow, '₡ '.$suma_precio_tipo_impuesto);
$startRow++;
$sheet->setCellValue('D'.$startRow, 'TOTAL');
$sheet->setCellValue('E'.$startRow, '₡ '.$suma_precio_total);
$startRow++;
// Aplicar bordes a los subtotales y totales
$sheet->getStyle('D' . ($startRow - 2) . ':E' . $startRow)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);

// Nota de la cotización
$sheet->setCellValue('A' . ($startRow + 2), 'Nota: Esta es una nota de la cotización');


// Enviar el archivo Excel al navegador para descargar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

$nombreXLSX = 'CO-'.date('dmY').'-'.$_REQUEST['id'].'.xlsx';

header('Content-Disposition: attachment;filename="'.$nombreXLSX.'"');
header('Cache-Control: max-age=0');

// Guardar el archivo en la salida del buffer
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
if(!isset($_REQUEST['ad']))
{
    exit;
}





function image_exists($path) {
    // Check if it's a URL
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        if (url_exists($path)) {
            return $path;
        } else {
            return false;
        }
    } else {
        // Check if it's a local file
        if (file_exists($path)) {
            return $path;
        } else {
            return false;
        }
    }
}

// Function to check if the URL exists
function url_exists($url)
{
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

function obtenerHoraConFormato($fecha) {
    // Convertir la fecha a timestamp
    $timestamp = strtotime($fecha);

    // Obtener la hora en formato de 12 horas con AM/PM
    $hora = date('g:i A', $timestamp);

    // Retornar la hora
    return $hora;
}




?>
