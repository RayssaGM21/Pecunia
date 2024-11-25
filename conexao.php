<?php
$host = 'localhost';
$usuarios = 'root';
$senha = 'mysql';
$banco = 'controle_financas';
$conn = mysqli_connect($host, $usuarios,$senha, $banco) or die ('Não foi possível');
?>