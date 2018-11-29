<?php
require_once('../conexao.php');
header('Access-Control-Allow-Origin: *');

$sql = "SET NAMES 'utf8'";
$conn->query($sql);


if($_GET['action']=='get'){


    $materialSQL = "SELECT * FROM materiais WHERE tipo = 'piso'";
    $materialQ = mysqli_query($conn,$materialSQL);

    if(!$materialQ){
        die("Falha na consulta ao banco. (materialQ)");
    }

    $result_json = array();

	while($row = mysqli_fetch_assoc($materialQ)){
		$result_json[] = $row;
    }

    echo json_encode($result_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}elseif ($_GET['action']=='addMaterial') {

    $nomeMaterial = $_POST['nomeMaterial'];
    $alturaMaterial = $_POST['alturaMaterial'];
    $comprimentoMaterial = $_POST['comprimentoMaterial'];

    $materialQ = $conn->prepare("INSERT INTO materiais (descricao,altura,comprimento,tipo) VALUES(?,?,?,'piso')");
    $materialQ->bind_param('sdd', $nomeMaterial,$alturaMaterial,$comprimentoMaterial);

    $materialQ->execute();

}elseif ($_GET['action']=='salvarItem') {
    $idItem = $_POST['iditem'];
    $descricao = $_POST['descricao'];
    $espessuraMassa = $_POST['massa'];
    $comComodo1 = $_POST['comComodo1'];
    $comComodo2 = $_POST['comComodo2'];

    $idMaterial = $_POST['material'];
    $total = $_POST['total'];

    $salvarItemQ = $conn->prepare("UPDATE itens SET descricao = ?, espessuraMassa = ?, alturaParede = ?, comprimentoParede = ?, idMaterial = ?, total = ?, umtotal = 'un' WHERE idItem = ?");
    $salvarItemQ->bind_param('sdddiii', $descricao, $espessuraMassa, $comComodo1, $comComodo2,  $idMaterial, $total, $idItem);

    $salvarItemQ->execute();

    
}elseif ($_GET['action']=='excluirItem') {
    $idItem = $_POST['iditem'];

    $delItemQ = $conn->prepare("DELETE FROM itens WHERE iditem = ?");
    $delItemQ->bind_param('i',$idItem);

    $delItemQ->execute();
    
}elseif ($_GET['action']=='getTotal') {

    $idProjeto = $_GET['idProjeto'];


    $pastasQ = $conn->prepare("SELECT idPasta FROM pastas WHERE idprojeto = ? AND tipo = 'piso'");
    $pastasQ->bind_param('i', $idProjeto);

    $pastasQ->execute();

    $result = $pastasQ->get_result();

    $result_json = array();



	while($row = mysqli_fetch_assoc($result)){
		$result_json[] = $row;
    }
    $sql = "";

    for($i=0;$i<sizeof($result_json);$i++){
        if($i==sizeof($result_json)-1){
            $sql .= $result_json[$i]['idPasta'];
        }else{
            $sql .= $result_json[$i]['idPasta'].",";
        }
    }

    $itensQ = $conn->prepare("SELECT sum(total) as total FROM itens WHERE idPasta IN (".$sql.")");

    $itensQ->execute();

    $result = $itensQ->get_result();

    $total = mysqli_fetch_assoc($result);

    echo $total['total'];
}
?>