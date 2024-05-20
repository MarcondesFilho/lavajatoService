<?php 
require_once("../../../conexao.php");

@session_start();
$id_usuario = $_SESSION['id'];

$veiculo = $_POST['veiculo'];
$funcionario = $_POST['funcionario'];
$servico = $_POST['servico'];

$query = $pdo->query("SELECT * from veiculos where id = '$veiculo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$cliente = $res[0]['cliente'];

$query = $pdo->query("SELECT * from servicos where id = '$servico'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $res[0]['valor'];
$comissao = $res[0]['comissao'];

if($tipo_comissao == "R$"){
	$valor_comissao = $comissao;
}else{
	$valor_comissao = $valor * $comissao / 100;
}


$pdo->query("INSERT INTO itens_servicos SET cliente = '$cliente', veiculo = '$veiculo', valor = '$valor', comissao = '$valor_comissao', funcionario = '$funcionario', usuario = '$id_usuario', data = curDate(), servico = '$servico', venda = '0' ");

echo 'Salvo com Sucesso';

?>