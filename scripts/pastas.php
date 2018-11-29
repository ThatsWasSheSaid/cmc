<?php
require_once('../conexao.php');
header('Access-Control-Allow-Origin: *');

$sql = "SET NAMES 'utf8'";
$conn->query($sql);



if($_GET['action']=='addPasta') {

    $nomePasta = $_POST['nomePasta'];
    $idProjeto = $_POST['idProjeto'];
    $tipo = $_POST['tipo'];

    $pastaQ = $conn->prepare("INSERT INTO pastas (descricao,idProjeto,tipo) VALUES(?,?,?)");
    $pastaQ->bind_param('sis', $nomePasta,$idProjeto,$tipo);

    $pastaQ->execute();

}elseif ($_GET['action']=='addItem') {

    $nomeItem = $_POST['nomeItem'];
    $idPasta = $_POST['idPasta'];

    $itemQ = $conn->prepare("INSERT INTO itens (descricao,idpasta) VALUES(?,?)");
    $itemQ->bind_param('si', $nomeItem,$idPasta);

    $itemQ->execute();

    $idItemQ = $conn->prepare("SELECT iditem FROM itens WHERE descricao = ? AND idpasta = ?");
    $idItemQ->bind_param('si', $nomeItem,$idPasta);

    $idItemQ->execute();

    $result = $idItemQ->get_result();

    $idItem = mysqli_fetch_assoc($result);

    echo $idItem['iditem'];
       
}else if($_GET['action']=='excluirPasta') {
    $idPasta = $_POST['idPasta'];

    $deelteItensQ = $conn->prepare("DELETE from itens WHERE idpasta = ?");
    $deelteItensQ->bind_param('i',$idPasta);

    $deelteItensQ->execute();

    $deeltePastaQ = $conn->prepare("DELETE from pastas WHERE idpasta = ?");
    $deeltePastaQ->bind_param('i',$idPasta);

    $deeltePastaQ->execute();
}
?>