<?php
class FiBodegasMovimientos {
    private $rowid;
    private $fk_bodega;
    private $fk_producto;
    private $tipo;
    private $valor;
    private $stock_actual;
    private $motivo;
    private $fecha;
    private $usuario;
    private $creado_fecha;
    private $creado_fk_usuario;
    private $borrado;
    private $borrado_fecha;
    private $borrado_fk_usuario;


    public function __construct($db) {
        $this->db = $db;
        $this->borrado = 0;
    }

    public function fetch_movimientos($id)
{
    $query = "SELECT fbm.*,
    fu.usuario as usuario_creador

    FROM fi_bodegas_movimientos fbm 

    left join fi_usuarios fu on fu.rowid = fbm.usuario 

    WHERE fbm.rowid = ? AND fbm.borrado = 0";
    $stmt = $this->dbh->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->rowid = $row['rowid'];
    $this->fk_bodega = $row['fk_bodega'];
    $this->fk_producto = $row['fk_producto'];
    $this->tipo = $row['tipo'];
    $this->valor = $row['valor'];
    $this->stock_actual = $row['stock_actual'];
    $this->motivo = $row['motivo'];
    $this->fecha = $row['fecha'];
    $this->usuario = $row['usuario'];
    $this->creado_fecha = $row['creado_fecha'];
    $this->creado_fk_usuario = $row['creado_fk_usuario'];
    $this->borrado = $row['borrado'];
    $this->borrado_fecha = $row['borrado_fecha'];
    $this->borrado_fk_usuario = $row['borrado_fk_usuario'];
}

}
