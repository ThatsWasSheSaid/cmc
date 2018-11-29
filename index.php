<?php
    //testa se esta logado
    session_start();
    if(!isset($_SESSION['usuarioLogado'])){
        header('location: login.php?action=login');
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
    <!-- JQuery -->
    <script src="_js/jquery.js"></script>
    <!-- JQuery Validate -->
    <script src="_js/jquery-validator.js"></script>
</head>
<body>
<?php require_once('pages/navbar.php')?>
    <div class="wrapper">
        <?php header('location: projetos.php'); ?>
    </div>
</body>
</html>