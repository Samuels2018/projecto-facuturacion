<?php
class Comerciales{

private $db;
public $nombre_comercial;
public $activo;
public $id;

  public function __construct ($db){         
                                 $this->db=$db;
                        

 }

 public function nuevo($usuario,$datos){
 global $_SESSION;
                                  $sql="insert into fi_comerciales
                                  (fk_entidad,
                                  activo,
                                  nombre_comercial)
 
                                  values 

                                 (:fk_entidad,
                                  :activo,
                                  :nombre_comercial) ";
                                 $dbh=$this->db->prepare($sql); 
                                 $dbh->bindValue(':fk_entidad',$_SESSION['Entidad'] ,PDO::PARAM_INT);
                                 $dbh->bindValue(':activo',(empty($_POST['activo']))? '1':$_POST['activo'] ,PDO::PARAM_INT);
                                 $dbh->bindValue(':nombre_comercial',(empty($_POST['nombre_comercial']))? ' ':$_POST['nombre_comercial'] ,PDO::PARAM_STR);
                                 $dbh->execute();
                                 $id=$this->db->lastInsertId(); 
                                 $this->id=$id;

                                 return $id;                                         
       

}

 public function update($usuario,$id){

                                 $sql="update fi_comerciales set nombre_comercial = :nombre_comercial,
                                  activo = :activo WHERE rowid = :rowid ";
                                 $dbh=$this->db->prepare($sql);  
                                 $dbh->bindValue(':nombre_comercial',(empty($_POST['nombre_comercial']))? '':$_POST['nombre_comercial'] ,PDO::PARAM_STR);
                                 $dbh->bindValue(':activo',(empty($_POST['activo']))? '0':$_POST['activo'] ,PDO::PARAM_INT);
                                 $dbh->bindValue(':rowid',$id,PDO::PARAM_INT);
                                 $dbh->execute();
                                 $this->id=$id;
                                 return $id;                                         
}
  
  public function fetch($id){
                               
                               
                                $sql="select * from fi_comerciales where rowid =".$id;
                                 $dbh=$this->db->prepare($sql);  
                                 //$dbh->bindValue(':rowid',$id,PDO::PARAM_INT);
                                 $dbh->execute();
                                 
                                 $datos=$dbh->fetch(PDO::FETCH_ASSOC);   
                                 
                                 $this->nombre_comercial    = $datos['nombre_comercial'];
                                 $this->id                  = $datos['rowid'];
                                 $this->activo              = $datos['activo'];
                                 

       

                                 
                                 
                                 
  }

function borrar($id){            $sql="delete from fi_comerciales where md5(rowid) = :rowid ";
                                 $dbh=$this->db->prepare($sql);  
                                 $dbh->bindValue(':rowid',$id,PDO::PARAM_STR);
                                 $dbh->execute();
                    }

            }

?>