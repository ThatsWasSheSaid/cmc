<?php
session_start();
header('Access-Control-Allow-Origin: *');
//inports
require_once('conexao.php');

$table = '<table style="width: 100%;border: 1px solid #757575;">
<tr class="item">
  <th>Material</th>
  <th>Quantidade</th> 
  <th>Unidade Medida</th>
</tr>
';

// $usuarioSQL = "SELECT * FROM usuarios WHERE idusuario =".$_SESSION['usuarioLogado'];
// $usuarioQ = mysqli_query($conn,$usuarioSQL);

// if(!$usuarioQ){
//     die("Falha na consulta ao banco. (materialQ)");
// }

// $usario = mysqli_fetch_assoc($usuarioQ);




$idProjeto = $_GET['idprojeto'];

$pastasQ = $conn->prepare("SELECT idPasta FROM pastas WHERE idprojeto = ?");
$pastasQ->bind_param('i', $idProjeto);

$pastasQ->execute();

$result = $pastasQ->get_result();

$result_json = array();



while($row = mysqli_fetch_assoc($result)){
	$result_json[] = $row;
}
$pastas = "";

for($i=0;$i<sizeof($result_json);$i++){
    if($i==sizeof($result_json)-1){
        $pastas .= $result_json[$i]['idPasta'];
    }else{
        $pastas .= $result_json[$i]['idPasta'].",";
    }
}

$materiaisQ = $conn->prepare("SELECT idmaterial,idpasta FROM `itens` WHERE idpasta IN ($pastas) GROUP BY idmaterial");

$materiaisQ->execute();

$result = $materiaisQ->get_result();

$result_json = array();

while($row = mysqli_fetch_assoc($result)){
	$result_json[] = $row;
}

for($i=0;$i<sizeof($result_json);$i++){

    $idMaterial = $result_json[$i]['idmaterial'];
    $idPasta = $result_json[$i]['idpasta'];

    $itensQ = $conn->prepare("SELECT sum(total) as total,b.descricao as nome, a.umtotal as um FROM itens a JOIN materiais b ON a.idmaterial = b.idmaterial WHERE a.idmaterial = ? AND a.idpasta = ?");
    $itensQ->bind_param('ii',$idMaterial, $idPasta);
    $itensQ->execute();
    
    $result = $itensQ->get_result();
    
    $itens_json = array();

    while($row = mysqli_fetch_assoc($result)){
        $itens_json[] = $row;
    }
    for($j=0;$j<sizeof($itens_json);$j++){
        $table .= "
        <tr class='item'>
            <th>".$itens_json[$j]['nome']."</th>
            <th>".$itens_json[$j]['total']."</th> 
            <th>".$itens_json[$j]['um']."</th>
        </tr>
        ";
    }
}

$table .= '</table>';

$documentTemplate = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <style>
    *{
        font-family: "Poppins", sans-serif;
    }
    .wrapper{
        text-align: center;
    }
    .logo-img{
        width: 200px;
        margin-bottom: 60px;
    }
    .info{
        text-align: left;
    }
    .info p{
        margin: 2px;
    }
    .info h3{
        margin: 20px;
        text-align: center;
    }
    .item{
        border-bottom: 1px solid #757575;
    }
    .table{
        margin-top: 30px;
    }
    
    </style>
</head>
<body>
    <div class="wrapper">
        <img class="logo-img" src="_assets/img/logo.png" alt="" >
        <div class="info">
            <p>Nome: Vinicius</p>
            <p>Email: vinicius_deastro@hotmail.com </p>
            <h3>Projeto casa popular</h3>
        </div>
        <div class="table">
        '.$table.'
        </div>
    </div>
</body>
</html>';

// inclusão da biblioteca
require_once 'plugins/dompdf/lib/html5lib/Parser.php';
require_once 'plugins/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'plugins/dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'plugins/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($documentTemplate);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

//echo $documentTemplate;

?>



