<?php 
require_once("../../../conexao.php");
$pagina = 'veiculos';
$id = $_POST['id'];

echo <<<HTML
<small>
HTML;
$query = $pdo->query("SELECT * FROM $pagina where cliente = '$id' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
echo <<<HTML
	<table class="table table-hover" id="tabela_arquivos">
		<thead> 
			<tr> 				
				<th>Modelo</th>
				<th class="esc">Marca</th>				
				<th class="esc">Placa</th>		
				<th>Cor</th>				
				<th>Excluir</th>
			</tr> 
		</thead> 
		<tbody> 
HTML;
for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id = $res[$i]['id'];
$placa = $res[$i]['placa'];
$data_cad = $res[$i]['data'];
$marca = $res[$i]['marca'];
$modelo = $res[$i]['modelo'];
$cor = $res[$i]['cor'];
$cor_hex = $res[$i]['cor_hex'];

$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));

$classe_b = '';
if($cor == 'Branco'){
	$classe_b = 'background:#5c5c5c; padding:2px';
}

echo <<<HTML
			<tr>					
				<td class=""><i class="fa fa-car" style="color:{$cor_hex}; {$classe_b}"></i> {$modelo}</td>
				<td class="esc">{$marca}</td>	
				<td class="esc">{$placa}</td>
				<td class="esc">{$cor}</td>				
				
				<td>

					<li class="dropdown head-dpdn2" style="display: inline-block;">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash-o text-danger"></i></a>
									<ul class="dropdown-menu">
										<li>
											<div class="notification_desc2">
												<p>Confirmar Exclusão? <a href="#" onclick="excluirVeiculo('{$id}')"><span class="text-danger">Sim</span></a></p>
												
											</div>
												
										
										</li>
										
									</ul>
								</li>
					
				</td>  
			</tr> 
HTML;
}
echo <<<HTML
		</tbody> 
	</table>
</small>
HTML;
}else{
	echo 'Não possui nenhum arquivo cadastrado!';
}

?>


<script type="text/javascript">


	$(document).ready( function () {
	    $('#tabela_arquivos').DataTable({
	    	"ordering": false,
	    	"stateSave": true,
	    });
	    $('#tabela_filter label input').focus();	    
	} );


	function excluirVeiculo(id){
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir-veiculo.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {

            $('#mensagem-veiculo').text('');
            $('#mensagem-veiculo').removeClass()
            if (mensagem.trim() == "Excluído com Sucesso") {                
                listarVeiculos();                
            } else {

                $('#mensagem-veiculo').addClass('text-danger')
                $('#mensagem-veiculo').text(mensagem)
            }


        },      

    });
}


</script>


