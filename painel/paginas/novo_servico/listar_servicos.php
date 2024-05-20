<?php 
require_once("../../../conexao.php");
$tabela = 'itens_servicos';
$data_hoje = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$id = @$_POST['id'];
$id_veiculo = @$_POST['id'];

if($id == ""){
	$id = 0;
}


$total_servicos = 0;

$query = $pdo->query("SELECT * FROM $tabela where veiculo = '$id' and venda = '0' and usuario = '$usuario_logado' order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela_servicos">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th class="esc">Valor</th> 
	<th class="esc">Profissional</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];	
	$valor = $res[$i]['valor'];
	$funcionario = $res[$i]['funcionario'];
	$servico = $res[$i]['servico'];
	
	$valorF = number_format($valor, 2, ',', '.');	
	


		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_servico = $res2[0]['nome'];
		}else{
			$nome_servico = 'Nenhum!';
		}



		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_func = $res2[0]['nome'];
		}else{
			$nome_func = 'Sem Referência!';
		}

		$total_servicos += $valor;



echo <<<HTML
<tr class="">
<td> {$nome_servico}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$nome_func}</td>

<td>
		


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirServico('{$id}', '{$id_veiculo}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>
		
	
		</td>
</tr>
HTML;

}

$total_servicosF = number_format($total_servicos, 2, ',', '.');

echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir-servicos"></div></small>
</table>
	
<div align="right">Total Serviços: <span class="verde">R$ {$total_servicosF}</span> </div>

</small>
HTML;


}else{
	echo '<small>Nenhum serviço ainda Lançado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready( function () {
	$('#valor_serv').val("<?=$total_servicos?>");  
	$('#valor_serv_neutro').val("<?=$total_servicos?>");   
   
} );

	function excluirServico(id, veiculo){
		$.ajax({
			url: 'paginas/' + pag + "/excluir_servico.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				listarServicos(veiculo);				
			}
		});
		
	}
</script>


