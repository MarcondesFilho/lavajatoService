<?php 
$tabela = 'servicos';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$valor = $_POST['valor'];
$comissao = $_POST['comissao'];
$descricao = $_POST['descricao'];
$id = $_POST['id'];

$valor = str_replace(',', '.', $valor);
$comissao = str_replace('%', '', $comissao);
$comissao = str_replace(',', '.', $comissao);


//validacao
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Nome já Cadastrado!';
	exit();
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, valor = :valor, comissao = :comissao, descricao = :descricao");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, valor = :valor, comissao = :comissao, descricao = :descricao where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":valor", "$valor");
$query->bindValue(":comissao", "$comissao");
$query->bindValue(":descricao", "$descricao");
$query->execute();

echo 'Salvo com Sucesso';
 ?>