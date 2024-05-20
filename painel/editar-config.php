<?php 
$tabela = 'config';
require_once("../conexao.php");

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$endereco = $_POST['endereco_sistema'];
$instagram = $_POST['instagram_sistema'];
$multa_atraso = $_POST['multa_atraso'];
$juros_atraso = $_POST['juros_atraso'];
$marca_dagua = $_POST['marca_dagua'];
$assinatura_recibo = $_POST['assinatura_recibo'];
$impressao_automatica = $_POST['impressao_automatica'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$tipo_comissao = $_POST['tipo_comissao'];
$taxa_sistema = $_POST['taxa_sistema'];

$multa_atraso = str_replace(',', '.', $multa_atraso);
$multa_atraso = str_replace('%', '', $multa_atraso);

$juros_atraso = str_replace(',', '.', $juros_atraso);
$juros_atraso = str_replace('%', '', $juros_atraso);

//foto logo
$caminho = '../img/logo.png';
$imagem_temp = @$_FILES['foto-logo']['tmp_name']; 

if(@$_FILES['foto-logo']['name'] != ""){
	$ext = pathinfo($_FILES['foto-logo']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
				
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto logo rel
$caminho = '../img/logo.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name']; 

if(@$_FILES['foto-logo-rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto icone
$caminho = '../img/icone.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name']; 

if(@$_FILES['foto-icone']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png' || $ext == 'PNG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto ass
$caminho = '../img/assinatura.jpg';
$imagem_temp = @$_FILES['assinatura_rel']['tmp_name']; 

if(@$_FILES['assinatura_rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['assinatura_rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'JPG'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, instagram = :instagram, multa_atraso = :multa_atraso, juros_atraso = :juros_atraso, marca_dagua = :marca_dagua, marca_dagua = :marca_dagua, assinatura_recibo = :assinatura_recibo, impressao_automatica = :impressao_automatica, cnpj = :cnpj_sistema, tipo_comissao = :tipo_comissao, taxa_sistema = :taxa_sistema where id = 1");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":multa_atraso", "$multa_atraso");
$query->bindValue(":juros_atraso", "$juros_atraso");
$query->bindValue(":marca_dagua", "$marca_dagua");
$query->bindValue(":assinatura_recibo", "$assinatura_recibo");
$query->bindValue(":impressao_automatica", "$impressao_automatica");
$query->bindValue(":cnpj_sistema", "$cnpj_sistema");
$query->bindValue(":tipo_comissao", "$tipo_comissao");
$query->bindValue(":taxa_sistema", "$taxa_sistema");
$query->execute();

echo 'Editado com Sucesso';
 ?>