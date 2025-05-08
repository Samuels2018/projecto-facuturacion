<?php

session_start();

include_once("../../conf/conf.php");
include_once(ENLACE_SERVIDOR."mod_citas_agenda/object/citas.object.php");

include_once ENLACE_SERVIDOR . "mod_productos/object/productos.object.php";
$productos = new Productos($dbh, $_SESSION['Entidad']);

$select_productos = $productos->obtener_lista_productos();

    if ($_POST['action'] == "agregar_producto"){
            $sql="insert into agenda_citas_productos (fk_producto) values (:fk_producto)  ";
            $db = $dbh->prepare($sql);
            $db->bindValue(":fk_producto", $_POST['facturar_fk_producto'], PDO::PARAM_INT);
            $db->execute();     
 
    }  else if ($_POST['action'] == "eliminar_producto"){
        $sql="delete from agenda_citas_productos  where rowid = :rowid ";
        $db = $dbh->prepare($sql);
        $db->bindValue(":rowid", $_POST['facturar_fk_producto'], PDO::PARAM_INT);
        $db->execute();     

    }
 






      $sql="    
   select 

                prod.label  ,
                p.rowid     ,
                
                (select total 
                from fi_productos_precios_clientes          WHERE  
                     fi_productos_precios_clientes.fk_producto = p.fk_producto 
                     
                     order by fi_productos_precios_clientes.rowid DESC limit 0,1) as total 

                from  agenda_citas_productos p 
                left join fi_productos prod on prod.rowid = p.fk_producto 

        ";

        $db = $dbh->prepare($sql);

        
        $db->execute() ;          
        $total      = 0;
        $i          = 0;

        while ($data = $db->fetch(PDO::FETCH_OBJ)){
            $i++;
            $tr.='<Tr>';
            $tr.="<Td  width='10%' >$i</td>";
            $tr.="<Td  width='50%' >{$data->label}</td>";
            $tr.="<Td  width='30%' >".numero($data->total)."</td>";            
            $tr.="<Td  width='10%' ><span class='badge badge-light-success'  OnClick='facturar_fk_producto_eliminar({$data->rowid})' style='cursor:pointer;'>🗑️</span></td>";            
            $tr.='</tr>';
            
            $total+=$data->total;
        }

        if ($i>0) {
            $tr.="<tr><Td colspan='2'>Total</td><td>".numero($total)."</td>";
        } else{
            $tr.="<tr><Td colspan='3'> Sin Items A Facturar  </td>";
        }
        

?>




<div class="row">                  
    <div class="col-md-8">
                <select class="form-control form-control-sm" id="facturar_fk_producto">
                  <option>Seleccionar</option>
                    <?php 
                    foreach ($select_productos as $key => $valor){
                      echo "<option value='{$valor['rowid']}' >{$valor['ref']}</option>";
                    }
                    ?>
                  </select>

    </div>
    <div class="col-md-4">
                <button type="button" class="btn _effect--ripple waves-effect waves-light" OnClick="facturar_fk_producto()" >Agregar</button>       
    </div>

</div>


<div class="row mt-5">                  
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table-bordered"><?php echo $tr;  ?></table>
         </div>                    
    </div>
</div>

<div class="row mt-5">                  
    <div class="col-md-12">
        <a href="<?php echo ENLACE_WEB; ?>factura/158">Ver Factura</a>    
    </div>
</div>