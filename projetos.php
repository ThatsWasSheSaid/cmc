<?php
header('Access-Control-Allow-Origin: *');
    //inports
    require_once('conexao.php');
    //testa se esta logado
    session_start();
    if(!isset($_SESSION['usuarioLogado'])){
        header('location: login.php?action=login');
    }
?>

<?php
    $projetosSQL = "SELECT * FROM projetos WHERE idusuario =".$_SESSION['usuarioLogado'];
    $projetosQ = mysqli_query($conn, $projetosSQL);
                
    if(!$projetosQ){
        die("Falha na consulta ao banco. (projetosQ)");
    }
?>

<?php

    if(isset($_GET['action']) && $_GET['action'] == 'criarProjeto'){

        $idUsuario = $_SESSION['usuarioLogado'];
        $descricao = $_POST['nome'];

        $addProjetoSQL = "INSERT INTO projetos (idusuario, descricao) VALUES($idUsuario, '$descricao')";
        echo $addProjetoSQL;
        $addProjetoQ = mysqli_query($conn, $addProjetoSQL);
                    
        if(!$addProjetoQ){
            die("Falha na consulta ao banco. (addProjetoQ)");
        }else{
            header('location: projetos.php');
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CMC - Projetos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <!-- main.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/main.css" />
    <!-- normalize.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/normalize.css" />
    <!-- projetos.css -->
    <link rel="stylesheet" type="text/css" media="screen" href="_css/projetos.css" />
    <!-- JQuery -->
    <script src="_js/jquery.js"></script>
    <!-- JQuery Validate -->
    <script src="_js/jquery-validator.js"></script>
    <!-- projetos.js -->
    <script src="_js/projetos.js"></script>
</head>
<body>
<?php require_once('pages/navbar.php'); ?>
    <div class="wrapper">
        <div class="projeto-wrapper"> 
            <?php while($index = mysqli_fetch_assoc($projetosQ)): ?>
                <a href="editaProjeto.php?idprojeto=<?php echo $index['idprojeto'];?>&tipo=tijolo" class="projeto">
                    <?php include('_assets/img/home.svg');?>
                    <p><?php echo $index['descricao']; ?></p>
                </a>
            <?php endwhile; ?>
            <div class="projeto" id="novoProjeto">
                <?php include('_assets/img/add.svg')?>
                <p>Adicionar Projeto</p>
            </div>
        </div>
    </div>
    <div class="addProjeto" id="addProjeto">
        <div class="addProjeto-form">
            <form action="projetos.php?action=criarProjeto" method="post" id="form-criarProjeto">
                <h2>Cadastrar Projeto</h2>
                <input type="text" name="nome" id="nome" placeholder="Nome do Projeto">
                <button type="button" class="danger" id="cancelaProjeto">Cancelar</button>
                <button type="submit">Criar Projeto</button>
            </form>
        </div>
    </div>
</body>
</html>