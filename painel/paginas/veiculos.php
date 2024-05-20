<?php 
$pag = 'veiculos';

if(@$veiculos == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="main-page margin-mobile">

	<div class="row" >	
		<div class="col-md-7" style="margin-bottom: 5px">
			<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Veículo</a>

			<li class="dropdown head-dpdn2" style="display: inline-block;">		
				<a href="#" data-toggle="dropdown"  class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none"><span class="fa fa-trash-o"></span> Deletar</a>

				<ul class="dropdown-menu">
					<li>
						<div class="notification_desc2">
							<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
						</div>
					</li>										
				</ul>
			</li>	


			
		</div>

		<form action="rel/veiculos_class.php" target="_blank" method="POST">
	

		<div class="col-md-3 padding_zero">
			<select class="form-control sel5" name="filtrar_cliente" id="filtrar_cliente" style="height: 31px" onchange="buscar()">
				<option value="">Filtrar por Cliente</option>
				<?php 
								$query = $pdo->query("SELECT * from clientes order by id asc");
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);
								if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
										echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
									}
								}
								?>	
			</select>
		</div>	

		<input type="hidden" name="tipo_data" id="tipo_data">

		<div class="col-md-2 botao_rel" align="right">
			<button type="submit" class="btn btn-success" title="Gerar Relatório"><i class="fa fa-file-pdf-o"></i> Relatório</button>
		</div>
		</form>
		
	</div>	





	<div class="bs-example widget-shadow" style="padding:15px" id="listar">

	</div>

</div>

<input type="hidden" id="ids">

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-veiculos">
				<div class="modal-body">


					<div class="row">						

						<div class="col-md-7" style="padding-right:1px">							
							<label>Cliente</label>
							<div id="listar_clientes">
																		
							</div>														
						</div>
						<div class="col-md-1" style="padding:1px; margin-top: 22px">		
							<button class="btn btn-primary" data-toggle="modal" data-target="#modalCliente"><span class="fa fa-plus " ></span></button>
						</div>

						<div class="col-md-4">						
								<div class="form-group"> 
									<label>Cor</label> 
									<select class="form-control" name="cor" id="cor" required>
										<option value="">Selecionar Cor</option>
										<option value="Branco">Branco</option>
										<option value="Preto">Preto</option>
										<option value="Azul">Azul</option>
										<option value="Verde">Verde</option>
										<option value="Cinza">Cinza</option>
										<option value="Laranja">Laranja</option>
										<option value="Amarelo">Amarelo</option>
										<option value="Rosa">Rosa</option>
										<option value="Roxo">Roxo</option>
										<option value="Fosco">Fosco</option>
										<option value="Cromado">Cromado</option>
									</select>
								</div>	
							</div>

						
					</div>

					<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Placa</label> 
									<input class="form-control" type="text" name="placa" id="placa" placeholder="Placa do Veículo">
								</div>	
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Modelo</label> 
									<input class="form-control" type="text" name="modelo" id="modelo" placeholder="Modelo do Veículo">
								</div>	
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Marca</label> 
									<input class="form-control" type="text" name="marca" id="marca" placeholder="Marca do Veículo">
								</div>	
							</div>
							



						</div>

						<div class="row" style="margin-top:-40px">
								

								<div class="col-md-12">						
								<div class="form-group"> 
									<label>Obs</label> 
									<input class="form-control" type="text" name="obs" id="obs" placeholder="OBS do Veículo">
								</div>	
							</div>

						
						</div>


										


					<input type="hidden" class="form-control" id="id" name="id">					

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>
				<div class="modal-footer">       
					<button id="btn_salvar" type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>





<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span>  </h4>
				<button id="btn-fechar-dados" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div class="row" style="margin-top: 0px">

					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>Cliente: </b></span><span id="cliente_dados"></span>
					</div>

					


					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Marca: </b></span><span id="marca_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Placa: </b></span><span id="placa_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Cadastro: </b></span><span id="data_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Cor: </b></span><span id="cor_dados"></span>
					</div>


					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>OBS: </b></span><span id="obs_dados"></span>
					</div>


					
				</div>
			</div>

		</div>
	</div>
</div>



<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="">Novo Cliente</span></h4>
				<button id="btn-fechar-cliente" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_cliente">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-8">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
						</div>

						<div class="col-md-4">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
						</div>		

						
					</div>


					<div class="row">

						<div class="col-md-3">							
								<label>Pessoa</label>
								<select name="tipo_pessoa" id="tipo_pessoa" class="form-control" onchange="mudarPessoa()">
									<option value="Física">Física</option>
									<option value="Jurídica">Jurídica</option>
								</select>							
						</div>		

						<div class="col-md-4">							
								<label>CPF / CNPJ</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF/CNPJ" >							
						</div>

						

						<div class="col-md-5">							
								<label>Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>

						
					</div>

					<div class="row">

						<div class="col-md-4">							
								<label>Nascimento</label>
								<input type="date" class="form-control" id="data_nasc" name="data_nasc" placeholder="" >							
						</div>

						<div class="col-md-8">							
								<label>Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Seu Endereço" >							
						</div>

					
					</div>

					
						

				<br>
				<small><div id="mensagem_cliente" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" id="btn_salvar_cliente" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>



	<script type="text/javascript">var pag = "<?=$pag?>"</script>
	<script src="js/ajax.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {

			$('.sel2').select2({
				dropdownParent: $('#modalForm')
			});

			$('.sel5').select2({
				
			});
		});
	</script>


	<script type="text/javascript">

		function marcarTodos(){
			let checkbox = document.getElementById('input-todos');
			var usuario = $('#id_permissoes').val();

			if(checkbox.checked) {
				adicionarPermissoes(usuario);		    
			} else {
				limparPermissoes(usuario);
			}
		}

	</script>


	<script type="text/javascript">
		function excluir(id){	
    $('#mensagem-excluir').text('Excluindo...')
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Excluído com Sucesso") {            	
                buscar();
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}
	</script>




	<script type="text/javascript">
		function buscar(){			
			filtrar_cliente = $('#filtrar_cliente').val();			
			listar(filtrar_cliente);
		}

		</script>


<script type="text/javascript">
			$("#form-veiculos").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/clientes/veiculos.php',
					type: 'POST',
					data: formData,

					success: function (mensagem) {

						$('#mensagem').text('');
						$('#mensagem').removeClass()
						if (mensagem.trim() == "Inserido com Sucesso") {                    
						limparCampos();
						  $('#btn-fechar').click();
                		listar();
               			 $('#mensagem').text('') 
					} else {
						$('#mensagem').addClass('text-danger')
						$('#mensagem').text(mensagem)
					}

				},

				cache: false,
				contentType: false,
				processData: false,

			});

			});
		</script>





<script type="text/javascript">
			$("#form_cliente").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				 $('#mensagem_cliente').text('Salvando...')
    				$('#btn_salvar_cliente').hide();

				$.ajax({
					url: 'paginas/clientes/salvar.php',
					type: 'POST',
					data: formData,

					success: function (mensagem) {

						$('#mensagem_cliente').text('');
						$('#mensagem_cliente').removeClass()
						if (mensagem.trim() == "Salvo com Sucesso") {                  
						listarClientes('ultimo');
						limparCamposCliente();
						  $('#btn-fechar-cliente').click();                		
               			 $('#mensagem_cliente').text('') 
					} else {
						$('#mensagem_cliente').addClass('text-danger')
						$('#mensagem_cliente').text(mensagem)
					}

					$('#btn_salvar_cliente').show();

				},

				cache: false,
				contentType: false,
				processData: false,

			});

			});
		</script>


	<script type="text/javascript">
		function limparCamposCliente(){			
    	$('#nome').val('');
    	$('#email').val('');
    	$('#telefone').val('');
    	$('#endereco').val('');
    	$('#cpf').val('');
    	$('#tipo_pessoa').val('Física').change();
    	$('#data_nasc').val('');
		}
	</script>

	<script type="text/javascript">
	function mudarPessoa(){
		var pessoa = $('#tipo_pessoa').val();
		if(pessoa == 'Física'){
			$('#cpf').mask('000.000.000-00');
			$('#cpf').attr("placeholder", "Insira CPF");
		}else{
			$('#cpf').mask('00.000.000/0000-00');
			$('#cpf').attr("placeholder", "Insira CNPJ");
		}
	}
</script>


	<script type="text/javascript">
		function listarClientes(ultimo){  
		    $.ajax({
		        url: 'paginas/' + pag + "/listar_clientes.php",
		        method: 'POST',
		        data: {ultimo},
		        dataType: "html",

		        success:function(result){
		            $("#listar_clientes").html(result);
		        }
		    });
		}
	</script>