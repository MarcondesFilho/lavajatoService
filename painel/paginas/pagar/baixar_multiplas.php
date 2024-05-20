<?php 
$tabela = 'pagar';
require_once("../../../conexao.php");

@session_start();
$id_usuario = $_SESSION['id'];

$id = $_POST['novo_id'];

$pdo->query("UPDATE $tabela SET data_pgto = curDate(), usuario_pgto = '$id_usuario', pago = 'Sim' where id = '$id' and pago != 'Sim'");

?>