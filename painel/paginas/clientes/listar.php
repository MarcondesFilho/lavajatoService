<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Telefone</th>	
	<th class="esc">Email</th>			
	<th class="esc">CPF / CNPJ</th>
	<th class="esc">Tipo Pessoa</th>
	<th class="esc">Data Cadastro</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$telefone = $res[$i]['telefone'];
	$email = $res[$i]['email'];	
	$endereco = $res[$i]['endereco'];
	$tipo_pessoa = $res[$i]['tipo_pessoa'];
	$cpf = $res[$i]['cpf'];
	
	$data_cad = $res[$i]['data_cad'];
	$data_nasc = $res[$i]['data_nasc'];

	$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
	$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));

	$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

	

echo <<<HTML
<tr>
<td>
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
{$nome}
</td>
<td class="esc">{$telefone}</td>
<td class="esc">{$email}</td>
<td class="esc">{$cpf}</td>
<td class="esc">{$tipo_pessoa}</td>
<td class="esc">{$data_cadF}</td>
<td>
	<big><a href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$tipo_pessoa}','{$data_nasc}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

	<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>

<big><a href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}', '{$data_cadF}','{$cpf}','{$tipo_pessoa}','{$data_nascF}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a></big>


	<big><a href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " style="color:#22146e"></i></a></big>

<big><a href="#" onclick="mostrarContas('{$nome}','{$id}')" title="Mostrar Contas"><i class="fa fa-money text-verde"></i></a></big>

<big><a class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp " style="color:green"></i></a></big>


	<big><a href="#" onclick="veiculo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-car " style="color:#22146e"></i></a></big>

</td>
</tr>
HTML;

}

}else{
	echo '<small>Não possui nenhum cadastro!</small>';
}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;
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
	function editar(id, nome, email, telefone, endereco, cpf, tipo_pessoa, data_nasc){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);
    	$('#email').val(email);
    	$('#telefone').val(telefone);
    	$('#endereco').val(endereco);    	
    	$('#cpf').val(cpf);
    	$('#tipo_pessoa').val(tipo_pessoa).change();
    	$('#data_nasc').val(data_nasc);

    	$('#modalForm').modal('show');
	}


	function mostrar(nome, email, telefone, endereco, data, cpf, tipo_pessoa, data_nasc){
		    	
    	$('#titulo_dados').text(nome);
    	$('#email_dados').text(email);
    	$('#telefone_dados').text(telefone);
    	$('#endereco_dados').text(endereco);
    	$('#cpf_dados').text(cpf);
    	$('#data_dados').text(data);
    	$('#pessoa_dados').text(tipo_pessoa);
    	$('#data_nasc_dados').text(data_nasc);
    	
    	$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#email').val('');
    	$('#telefone').val('');
    	$('#endereco').val('');
    	$('#cpf').val('');
    	$('#tipo_pessoa').val('Física').change();
    	$('#data_nasc').val('');

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
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
		}else{
			$('#btn-deletar').show();
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

	function arquivo(id, nome){
	    $('#id-arquivo').val(id);    
	    $('#nome-arquivo').text(nome);
	    $('#modalArquivos').modal('show');
	    $('#mensagem-arquivo').text(''); 
	    $('#arquivo_conta').val('');
	    listarArquivos();   
	}	



	function mostrarContas(nome, id){
		    	
    	$('#titulo_contas').text(nome); 
    	$('#id_contas').val(id); 	
    	    	
    	$('#modalContas').modal('show');
    	listarDebitos(id);
    	
	}


	function listarDebitos(id){

		 $.ajax({
        url: 'paginas/' + pag + "/listar_debitos.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(result){
            $("#listar_debitos").html(result);           
        }
    });
	}


	function veiculo(id, nome){
	    $('#id-veiculo').val(id);    
	    $('#nome-veiculo').text(nome);
	    $('#modalVeiculos').modal('show');
	    $('#mensagem-veiculo').text(''); 
	    $('#marca').val('');
	    $('#placa').val('');
	    $('#modelo').val('');
	    $('#obs').val('');
	    $('#cor').val('');
	    listarVeiculos();   
	}	
</script>