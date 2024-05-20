<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$id = $_POST['id'];


$pdo->query("DELETE FROM veiculos WHERE cliente = '$id' ");
$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
echo 'Excluído com Sucesso';
?>