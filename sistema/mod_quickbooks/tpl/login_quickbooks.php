
<?php

// Url QuickBooks Dinamico - @rojasarmando - 14-06-2024
const SISTEMA_QUICKBOOK_ID = 1 ;
$url_quickbook = $Entidad->obtener_url_externa(SISTEMA_QUICKBOOK_ID);
//-----------------------------------

// Url QuickBooks Dinamico - @rojasarmando - 17-06-2024

$entidad_id =  $_SESSION["Entidad"];
$client_id = $_ENV['QUICKBOOKS_CLIENT_ID'];
$scope = $_ENV['QUICKBOOKS_SCOPE']; 
$redirect_uri = $_ENV['QUICKBOOKS_REDIRECT_URI'] ;
$response_type = $_ENV['QUICKBOOKS_RESPONSE_TYPE'];
$state = $_ENV['QUICKBOOKS_STATE'];

$SISTEMA_QUICKBOOK_ID = 1;
$url_quickbook = $Entidad->obtener_url_externa($SISTEMA_QUICKBOOK_ID);

$redirect_uri = $url_quickbook . '/webhook';


$url_connect_qb = "https://appcenter.intuit.com/connect/oauth2?client_id={$client_id}&scope={$scope}&redirect_uri=$redirect_uri&response_type={$response_type}&state={$state}";

//-----------------------------------

?>
<div class="col-md-8" style="float: right;">
<a href="<?= $url_connect_qb ?>"
class="btn btn-success" id="btn-quickbooks" onclick="window.open(this.href, 'ventanaQuickBooks', 
                                                     'width=500,height=600,scrollbars=yes,resizable=yes'); return false;"
   ><i class="fa fa-refresh" aria-hidden="true"></i>
 Sincronizar</a>
</div>
<script>
document.querySelector("#btn-quickbooks").addEventListener("click",async function(){
 await fetch(`<?= $url_quickbook ?>/prepare-company`,{
 	"method":"POST",
   	"headers":{"Content-Type":"application/json","Accept":"application/json"},
 	"body":JSON.stringify({"entidad_id":<?= $entidad_id ?>})})
  window.open('<?= $url_connect_qb ?>', '_blank', 
        'width=500,height=600,scrollbars=yes,resizable=yes'); 
  		return false;
})
</script>





