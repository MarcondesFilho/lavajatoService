<?php 
$tabela = 'veiculos';
require_once("../../../conexao.php");

$p1 = @$_POST['p1'];
if($p1 == ""){
	$filtro_cliente = '';
}else{
	$filtro_cliente = "where cliente = '$p1'";
}

$query = $pdo->query("SELECT * from $tabela $filtro_cliente order by id desc");



$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Modelo</th>	
	<th class="">Marca</th>	
	<th class="esc">Placa</th>
	<th class="esc">Cor</th>	
	<th class="esc">Cliente</th>	
	<th class="esc">Telefone</th>	
			
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$placa = $res[$i]['placa'];
$data_cad = $res[$i]['data'];
$marca = $res[$i]['marca'];
$modelo = $res[$i]['modelo'];
$cor = $res[$i]['cor'];
$cor_hex = $res[$i]['cor_hex'];
$obs = $res[$i]['obs'];
$cliente = $res[$i]['cliente'];
$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));

	

$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_cliente = $res2[0]['nome'];
	$telefone_cliente = $res2[0]['telefone'];
}else{
	$nome_cliente = 'Sem Usuário';
	$telefone_cliente = "";
}

$classe_b = '';
if($cor == 'Branco'){
	$classe_b = 'background:#5c5c5c; padding:2px';
}

$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_cliente);

echo <<<HTML
<tr>
<td class="">
<i class="fa fa-car" style="color:{$cor_hex}; {$classe_b}"></i> {$modelo}</td>
				<td class="esc">{$marca}</td>	
				<td class="esc">{$placa}</td>
				<td class="esc">{$cor}</td>	
				<td class="esc">{$nome_cliente}</td>
				<td class="esc">{$telefone_cliente}</td>

<td>
	<big><a href="#" onclick="editar('{$id}','{$cliente}','{$cor}','{$placa}','{$modelo}','{$marca}','{$obs}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

	<big><a class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp " style="color:green"></i></a></big>

	<big><a href="#" onclick="servico('{$id}','{$nome_cliente}','{$modelo}','{$marca}')" title="Novo Serviço"><i class="fa fa-money text-verde"></i></a></big>

	

</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>

</table>


HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>


<script type="text/javascript">
	function editar(id, cliente, cor, placa, modelo, marca, obs){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#placa').val(placa);
    	$('#modelo').val(modelo);
    	
    	$('#marca').val(marca);    	
    	$('#cor').val(cor).change();    	
    	$('#obs').val(obs);   

    	listarClientes('');	

    	setTimeout(function() {
		  $('#cliente').val(cliente).change();
		}, 700)

    	

    	$('#modalForm').modal('show');
	}


	
	function limparCampos(){
		$('#id').val('');
    	$('#marca').val('');
    	$('#modelo').val(''); 
    	$('#obs').val('');    	
    	$('#placa').val('');
    	$('#cliente').val('0').change();
    	$('#cor').val('').change();
    	listarClientes('');

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
    	$('#btn-baixar').hide();	
	}

	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
			$('#btn-baixar').hide();
		}else{
			$('#btn-deletar').show();
			$('#btn-baixar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluir(id[i]);			
		}

		limparCampos();
	}


	function servico(id, cliente, modelo, marca){
			    	
    	$('#titulo_servico').text(cliente + ' / ' + marca + ' ' + modelo);    	
    	$('#id_servico').val(id); 

    	$('#valor_serv_agd_restante').val(''); 	
    	$('#desconto').val(''); 	
    	
    	$('#modalServico').modal('show');
    	listarServicos(id);
	}


	
</script>