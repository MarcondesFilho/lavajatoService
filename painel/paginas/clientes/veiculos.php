<?php 
$tabela = 'veiculos';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['cliente'];
$placa = $_POST['placa'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$obs = $_POST['obs'];
$cor = $_POST['cor'];

if($cor == "Branco"){
	$cor_hex = "#ebebeb";
}

if($cor == "Preto"){
	$cor_hex = "#000";
}

if($cor == "Azul"){
	$cor_hex = "#2e51db";
}

if($cor == "Verde"){
	$cor_hex = "#26bd35";
}

if($cor == "Cinza"){
	$cor_hex = "#a3a3a3";
}

if($cor == "Laranja"){
	$cor_hex = "#d98525";
}

if($cor == "Amarelo"){
	$cor_hex = "#dced1c";
}

if($cor == "Rosa"){
	$cor_hex = "#f229b3";
}

if($cor == "Roxo"){
	$cor_hex = "#b115c2";
}

if($cor == "Fosco"){
	$cor_hex = "#3b3b3b";
}

if($cor == "Cromado"){
	$cor_hex = "#a6a4a4";
}

$query = $pdo->prepare("INSERT INTO $tabela SET placa = :placa,  marca = :marca,  modelo = :modelo,  obs = :obs,  cor = :cor, cliente = '$id', data = curDate(), cor_hex = '$cor_hex'");

$query->bindValue(":placa", "$placa");
$query->bindValue(":marca", "$marca");
$query->bindValue(":modelo", "$modelo");
$query->bindValue(":obs", "$obs");
$query->bindValue(":cor", "$cor");
$query->execute();

echo 'Inserido com Sucesso';

?>