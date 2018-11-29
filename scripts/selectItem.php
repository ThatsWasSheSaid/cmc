<?php
require_once('../conexao.php');
header('Access-Control-Allow-Origin: *');
if($_GET['iditem']){

    $itemSQL = "SELECT * FROM itens WHERE iditem = ".$_GET['iditem'];
    $itemQ = mysqli_query($conn,$itemSQL);

    if(!$itemQ){
        die("Falha na consulta ao banco. (pastasQ)");
    }

    echo json_encode(mysqli_fetch_assoc($itemQ));
}
?>