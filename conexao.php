<?php

$host = "localhost";
$usuario = "root";
$senha = "";

$conn = mysqli_connect($host,$usuario,$senha,"cmc");

if(!$conn){
    die("Erro ao conectar-se com o banco de dados.");
}
?>