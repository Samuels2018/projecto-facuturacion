<?Php 

        
        
  SESSION_START();

    if (empty($_SESSION['usuario_ex'])){
            exit(1);
    }
  
  
  include_once('../../conf/conf.php');
       $sql = 
       "SELECT 
       citas.rowid
    ,  citas.fecha
    ,  citas.hora_inicio
    ,  citas.hora_fin    
    ,  CL.nombre 
    ,  CL.primer_apellido 
    ,  CL.segundo_apellido 
    ,  CL.telefono 
    ,  CL.correo
    ,  citas.fk_cliente as fk_paciente 
    

        FROM fi_citas   citas    
        LEFT  JOIN fi_pacientes             CL ON CL.rowid    = citas.fk_cliente
    
        where citas.rowid = :rowid ";
    
    $db = $dbh->prepare($sql);
    $db->bindValue(":rowid",  $_POST['cita'] ,               PDO::PARAM_INT);
    $db->execute();
    $Cita = $db->fetch(PDO::FETCH_OBJ);
    

?>

    <div class="modal-header">
        <h4 class="modal-title"><b>Cita <?php echo $Cita->rowid." ".$Cita->nombre;  ?></b> </h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      
      
      <div class="modal-body">
  <div class="row">
      <div class="col-md-12">
          <table width="100%" border='0'>
             <?php
             /* <tr>
                  <td width="25%">
                  <center>
                     <table>
                      <tr>
                        <td>
                          <div style='width:18px;height:18px;boder-color:black;background-color:<?php echo $Cita->estado_color; ?>;float:left;margin-right:5px;'></div>
                        </td>
                        <td>
                          <?php echo $Cita->estado; ?>
                        </td>
                        </tr>
                    </table>
                  </center>
                  </td>
                  <td width="25%" >
                   
                  </td>
                  
                 </tr>
                <tr><td colspan='3'><br></td></tr>
                <!--------------------------------- Vehiculo----------------------------------------------->
                <!---------------------------------------------------------------------------------------->
                <!--------------------------------- Vehiculo----------------------------------------------->
                <!---------------------------------------------------------------------------------------->
                
                */
                ?>
                
                <tr>
                  <td width="25%" rowspan='2'>
                    <center><img  width="80px" class="img-circle user-img-circle"  src='https://nginx6.avantecds.es//bootstrap_sunrey/assets/img/julio.jpeg'>  </center>
                  </td>
                  <td width="25%" colspan='2'>
                    <h3 style='color:orange'><?php echo $Cita->nombre . " " . $Cita->primer_apellido. " " . $Cita->segundo_apellido; ?></h3>
                  </td>
                  
                </tr>
            
                <tr>
                  <td width="25%" >
                    <i class="fa fa-fw fa-calendar"></i>  <?php echo date('d-m-Y', strtotime($Cita->fecha)); ?> 
                  </td>
                  <td width="25%"  >
                    <strong><img src="https://saicor.co.cr/images/sistema/reloj.png" width="14px"> <?php echo date('h:i A', strtotime($Cita->hora_inicio)); ?></strong>
                  </td>
                </tr>
                
                
              

                <tr><td colspan='3'><br></td></tr>
                <!--------------------------------- Usuario----------------------------------------------->
                <!---------------------------------------------------------------------------------------->
                <!--------------------------------- Usuario----------------------------------------------->
                <!---------------------------------------------------------------------------------------->
                  <tr>
                  <td width="25%" rowspan='2' >
                    <center><img class="img-radius"  width='50px' height='50px' src='https://nginx6.avantecds.es//bootstrap_sunrey/assets/img/logo.png'></center>
                  </td>
                  <td width="25%">
                    <?php echo $data->first_name . " " . $data->last_name; ?>
                  </td>
                </tr>
                <Tr>
                 <td width="25%" >
                    <strong><i class="fa fa-fw fa-phone"></i>  <?php echo (empty(trim($Cita->telefono))) ? '-' : $Cita->telefono; ?>  </strong>
                  </td>
                  <td width="25%" >
                    <i class="fa fa-fw fa-envelope"></i> <?php echo (empty(trim($Cita->correo))) ? '-' : $Cita->correo; ?>
                  </td>

                 </tr>
               <tr>
                  <Td></Td>
                  <td  colspan='2'>
                        <?php if ($data->citas == 1) {?>
                        <i class="fa fa-fw fa-folder"></i> Unica Cita en <?php echo ($_SESSION['nombre']); ?>

                        <?php } else {?>
                        <i class="fa fa-fw fa-folder-open"></i><?php echo $data->citas; ?> Citas en <Strong>Salamanca</strgong>

                        <?php }?>
                  </td>

               


                 </tr>
                <!--------------------------------- Usuario----------------------------------------------->
                <!----------------------------- ----------------------------------------------------------->
                <!--------------------------------- Usuario----------------------------------------------->
                <!---------------------------------------------------------------------------------------->

              </table>
          </div>
      </div>
  </div>


      
      <div class="modal-footer">
        <!-- SAVE -->
        <button class="btn btn-success notika-btn-info waves-effect" onclick="#" style="font-weight: bold;">Facturar</button>

        <button class="btn btn-success notika-btn-success waves-effect" onclick="window.location='<?php echo ENLACE_WEB; ?>paciente/<?php echo $Cita->fk_paciente; ?>'" style="font-weight: bold;"> <i class="fa fa-fw fa-stethoscope"></i> Expediente</button>
        <!-- CLOSE -->
        <button class="btn btn-danger notika-btn-danger waves-effect" data-dismiss="modal" style="font-weight: bold;">Cerrar</button>
      </div>