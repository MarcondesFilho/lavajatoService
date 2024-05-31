<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$id = @$_POST['id'];
$id_veiculo = @$_POST['id'];
$servico_id = @$_POST['servico'];

$query = $pdo->query("SELECT * from veiculos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    $cliente = $res[0]['cliente'];
} else {
    echo "Erro: Veículo não encontrado.";
    exit();
}

$data_pgto = $_POST['data_pgto'];
$valor_serv = $_POST['valor_serv'];
$valor_serv = str_replace(',', '.', $valor_serv);
$funcionario = $usuario_logado;

$desconto = $_POST['desconto'];
$desconto = str_replace(',', '.', $desconto);

if($desconto == ""){
	$desconto = 0;
}

$pgto = @$_POST['pgto'];
$obs = @$_POST['obs'];

$valor_serv_restante = @$_POST['valor_serv_agd_restante'];
$valor_serv_restante = str_replace(',', '.', $valor_serv_restante);
$pgto_restante = @$_POST['pgto_restante'];
$data_pgto_restante = @$_POST['data_pgto_restante'];

if($valor_serv_restante == ""){
	$valor_serv_restante = 0;
}

$valor_total_servico = $valor_serv + $valor_serv_restante;


$query = $pdo->query("SELECT * FROM formas_pgto where id = '$pgto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $res[0]['taxa'];
$taxa = 0;
$subtotal = $valor_serv;
if($valor_taxa > 0 and strtotime($data_pgto) <=  strtotime($data_atual)){
	$taxa = $valor_serv * ($valor_taxa / 100);
	if($taxa_sistema == 'Cliente'){		
		$subtotal = $valor_serv + $taxa;
	}else{		
		$subtotal = $valor_serv - $taxa;
	}
	
}



$query = $pdo->query("SELECT * FROM formas_pgto where id = '$pgto_restante'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$res[0]['taxa'];
$taxa_restante = 0;
$subtotal_restante = $valor_serv_restante;
if($valor_serv_restante > 0){
if($valor_taxa > 0 and strtotime($data_pgto_restante) <=  strtotime($data_atual)){
	$taxa_restante = $valor_serv_restante * ($valor_taxa / 100);
	if($taxa_sistema == 'Cliente'){		
		$subtotal_restante = $valor_serv_restante + $taxa_restante;
	}else{
		$subtotal_restante = $valor_serv_restante - $taxa_restante;
	}
	
}
}


if(strtotime($data_pgto) <=  strtotime($data_atual)){
	$pago = 'Sim';
	$data_pgto2 = ", data_pgto = '$data_pgto'";
	$usuario_baixa = $usuario_logado;	
	
}else{
	$pago = 'Não';
	$data_pgto2 = '';
	$usuario_baixa = 0;

}


//dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $res2[0]['telefone'];
$nome_cliente = $res2[0]['nome'];


$descricao = 'Serviço '.$nome_cliente;


//inserção da venda
$total_venda = $subtotal + $subtotal_restante + $desconto;
$subtotal_venda = $subtotal + $subtotal_restante;

$pdo->query("INSERT INTO vendas SET cliente = '$cliente', veiculo = '$id_veiculo', total = '$total_venda', desconto = '$desconto', subtotal = '$subtotal_venda', data = curDate(), hora = curTime(), usuario = '$usuario_logado', obs = '$obs'");
$id_venda = $pdo->lastInsertId();


if($valor_serv_restante > 0){
if(strtotime($data_pgto_restante) <=  strtotime($data_atual)){
	$pago_restante = 'Sim';
	$data_pgto2_restante = ", data_pgto = '$data_pgto_restante'";
	$usuario_baixa_restante = $usuario_logado;
}else{
	$pago_restante = 'Não';
	$data_pgto2_restante = '';
	$usuario_baixa_restante = 0;
}


//lançar o restante
$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', referencia = 'Serviço', valor = '$valor_serv_restante', data_lanc = curDate(), vencimento = '$data_pgto_restante', usuario_lanc = '$usuario_logado', usuario_pgto = '$usuario_baixa', arquivo = 'sem-foto.png', cliente = '$cliente', pago = '$pago_restante', forma_pgto = '$pgto_restante', frequencia = '0', taxa = '$taxa_restante', subtotal = '$subtotal_restante', id_ref = '$id_veiculo', venda = '$id_venda', desconto = '$desconto' $data_pgto2_restante");	
}

$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', referencia = 'Serviço', valor = '$valor_serv', data_lanc = curDate(), vencimento = '$data_pgto', usuario_lanc = '$usuario_logado', usuario_pgto = '$usuario_baixa', arquivo = 'sem-foto.png', cliente = '$cliente', pago = '$pago', forma_pgto = '$pgto', frequencia = '0', taxa = '$taxa', subtotal = '$subtotal', id_ref = '$id_veiculo', venda = '$id_venda', desconto = '$desconto' $data_pgto2");

// Calcular a comissão e lançar como despesa
$query_comissao = $pdo->query("SELECT comissao FROM servicos WHERE id = '$servico_id'");
$res_comissao = $query_comissao->fetchAll(PDO::FETCH_ASSOC);
if (count($res_comissao) > 0) {
    $comissao_percentual = $res_comissao[0]['comissao'];
    $valor_comissao = $valor_serv * ($comissao_percentual / 100);
    $descricao_comissao = 'Comissão do serviço '.$nome_cliente;

	$pdo->query("INSERT INTO pagar SET descricao = '$descricao_comissao', fornecedor = 0, funcionario = '$funcionario', valor = '$valor_comissao', vencimento = '$data_pgto', data_lanc = curDate(), forma_pgto = '$pgto', frequencia = '0', usuario_lanc = '$usuario_logado', usuario_pgto = 0, pago = 'Não'");
} else {
    echo "Erro: Comissão não encontrada para o serviço.";
    exit();
}

$pdo->query("UPDATE itens_servicos SET venda = '$id_venda' where venda = '0' and veiculo = '$id_veiculo'");



echo 'Salvo com Sucesso'; 

?>