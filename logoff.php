<?php
    session_start();
    unset($_SESSION["usuarioLogado"]);
    header('location: login.php?action=login');
?>