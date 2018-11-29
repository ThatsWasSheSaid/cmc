<?php
require_once('../conexao.php');
header('Access-Control-Allow-Origin: *');

$sql = "SET NAMES 'utf8'";
$conn->query($sql);


if($_GET['action']=='get'){


    $tijoloSQL = "SELECT * FROM materiais WHERE tipo = 'tijolo'";
    $tijoloQ = mysqli_query($conn,$tijoloSQL);

    if(!$tijoloQ){
        die("Falha na consulta ao banco. (tijoloQ)");
    }

    $result_json = array();

	while($row = mysqli_fetch_assoc($tijoloQ)){
		$result_json[] = $row;
    }

    echo json_encode($result_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}elseif ($_GET['action']=='addTijolo') {

    $nomeTijolo = $_POST['nomeTijolo'];
    $alturaTijolo = $_POST['alturaTijolo'];
    $comprimentoTijolo = $_POST['comprimentoTijolo'];
    $espessuraTijolo = $_POST['espessuraTijolo'];

    $tijoloQ = $conn->prepare("INSERT INTO materiais (descricao,altura,comprimento,espessura,tipo) VALUES(?,?,?,?,'tijolo')");
    $tijoloQ->bind_param('sddd', $nomeTijolo,$alturaTijolo,$comprimentoTijolo,$espessuraTijolo);

    $tijoloQ->execute();

}elseif ($_GET['action']=='salvarItem') {
    $idItem = $_POST['iditem'];
    $idPasta = $_POST['idpasta'];
    $descricao = $_POST['descricao'];
    $espessuraMassa = $_POST['massa'];
    $alturaParede = $_POST['altParede'];
    $comprimentoParede = $_POST['comParede'];

    if(isset($_POST['deitado'])){
        $tijoloDeitado = 1;
    }else{
        $tijoloDeitado = 0;
    }

    $idMaterial = $_POST['tijolo'];
    $total = $_POST['total'];

    $salvarItemQ = $conn->prepare("UPDATE itens SET descricao = ?, espessuraMassa = ?, alturaParede = ?, comprimentoParede = ?, tijoloDeitado = ?, idMaterial = ?, total = ?, umtotal = 'un' WHERE idItem = ?");
    $salvarItemQ->bind_param('sdddiiii', $descricao, $espessuraMassa, $alturaParede, $comprimentoParede, $tijoloDeitado, $idMaterial, $total, $idItem);

    $salvarItemQ->execute();

    
}elseif ($_GET['action']=='excluirItem') {
    $idItem = $_POST['iditem'];

    $delItemQ = $conn->prepare("DELETE FROM itens WHERE iditem = ?");
    $delItemQ->bind_param('i',$idItem);

    $delItemQ->execute();
    
}elseif ($_GET['action']=='getTotal') {

    $idProjeto = $_GET['idProjeto'];


    $pastasQ = $conn->prepare("SELECT idPasta FROM pastas WHERE idprojeto = ? AND tipo = 'tijolo'");
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