<?php 
$pag = 'novo_servico';

if(@$novo_servico == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="main-page margin-mobile">

	<div class="row" >	
		<div class="col-md-2" style="margin-bottom: 5px">
			<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Veículo / Cliente</a>		

			
		</div>

			

		<div class="col-md-3">
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





<!-- Modal Servico -->
<div class="modal fade" id="modalServico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg largura_modal_grande" >
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_servico"></span>  </h4>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form id="form-servico">
				<div class="modal-body">	

						<div class="row">
								

								<div class="col-md-8" style="border-right: 1px solid #6e6d6d; overflow: scroll; height:auto; max-height: 350px; scrollbar-width: thin;">				
								

									<div class="col-md-12" style="border-top: 1px solid #cecece; margin-bottom: 5px;">	

										<div class="col-md-6" style="margin-left: -17px; margin-top: 10px">						
							<div class="form-group"> 
								<label>Serviço</label> 
								<select class="form-control sel20" id="servico" name="servico" style="width:100%;" required> 

									<?php 
									$query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
							</div>						
						</div>


						<div class="col-md-5" style="margin-top: 10px">						
							<div class="form-group"> 
								<label>Profissional</label> 
								<select class="form-control sel20" id="funcionario" name="funcionario" style="width:100%;" required onchange=""> 

									<?php 
									$query = $pdo->query("SELECT * FROM usuarios ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
							</div>						
						</div>

						<div class="col-md-1" style="margin-top: 30px">	
							<a href="#" onclick="inserirServico()" class="btn btn-primary"><i class="fa fa-plus"></i></a>
						</div>


						<div class="col-md-12" style="border: 1px solid #5c5c5c; margin-bottom: 5px; " id="listar_servicos">
							
						</div>


						<div class="col-md-12" style="margin-bottom: 10px">			
							
									<label>Observações</label> 
									<input class="form-control" type="text" name="obs" id="obs_servico" placeholder="OBS do Serviço">
								
							</div>
							<input type="hidden" id="valor_serv_neutro">


									</div>

							</div>


							<div class="col-md-4">
							<div class="col-md-5" id="nasc">						
							<div class="form-group"> 
								<label><small>Valor</small> </label> 
								<input type="text" class="form-control inputs_form" name="valor_serv" id="valor_serv"> 
							</div>						
						</div>

						<div class="col-md-7" id="nasc" >						
							<div class="form-group"> 
								<label><small>Data PGTO</small></label> 
								<input type="date" class="form-control inputs_form" name="data_pgto" id="data_pgto" value="<?php echo date('Y-m-d') ?>"> 
							</div>						
						</div>	


						<div class="col-md-12" style="border-bottom: 1px solid #a8a7a7">						
							<div class="form-group"> 
								<label><small>Forma PGTO</small></label> 
								<select class="form-control inputs_form" id="pgto" name="pgto" style="width:100%;" required> 

									<?php 
									$query = $pdo->query("SELECT * FROM formas_pgto");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
							</div>						
						</div>


							

						
					



						<div class="col-md-5" id="" style="margin-top: 10px">						
							<div class="form-group"> 
								<label><small>Valor Restante</small> </label> 
								<input type="text" class="form-control inputs_form" name="valor_serv_agd_restante" id="valor_serv_agd_restante" onkeyup="abaterValor()"> 
							</div>						
						</div>


							<div class="col-md-7" id="" style="margin-top: 10px">						
							<div class="form-group"> 
								<label><small>Data PGTO Restante</small></label> 
								<input type="date" class="form-control inputs_form" name="data_pgto_restante" id="data_pgto_restante" value=""> 
							</div>						
						</div>


							<div class="col-md-12" style="border-bottom: 1px solid #a8a7a7" >						
							<div class="form-group"> 
								<label><small>Forma PGTO Restante</small></label> 
								<select class="form-control inputs_form" id="pgto_restante" name="pgto_restante" style="width:100%;" > 
									<option value="">Selecionar Pgto</option>
									<?php 
									$query = $pdo->query("SELECT * FROM formas_pgto");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
							</div>						
						</div>

						<div class="col-md-12" style="border-bottom: 1px solid #a8a7a7" >	
							<div class="form-group"> 
								<label><small>Desconto</small></label> 
								<input type="text" class="form-control inputs_form" name="desconto" id="desconto" value="" onkeyup="abaterValor()"> 
							</div>	
						</div>

			
					

						<div class="col-md-12" align="right" style="margin-top: 15px">							

					<button id="btn_salvar_servico" type="submit" class="btn btn-primary">Salvar</button>
						</div>	

						</div>

						
						</div>


										


					<input type="hidden" class="form-control" id="id_servico" name="id">					

					<br>
					<small><div id="mensagem_servico" align="center"></div></small>
				</div>
			
				
				</form>

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

			$('.sel20').select2({
				dropdownParent: $('#modalServico')
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



<script type="text/javascript">
	function inserirServico(){	
		$("#mensagem_servico").text('');
		var servico = $("#servico").val();
		var funcionario = $("#funcionario").val();
		var veiculo = $("#id_servico").val();
		var id = $('#id_servico').val();
		
	
		if(servico == ""){
			alert("Selecione um Serviço")
			return;
		}

		if(funcionario == ""){
			alert("Selecione um Profissional")
			return;
		}

		$.ajax({
			url: 'paginas/' + pag + "/inserir_servico.php",
			method: 'POST',
			data: {servico, funcionario, veiculo},
			dataType: "text",

			success:function(result){
				if(result.trim() === 'Salvo com Sucesso'){
					listarServicos(id)
					
				}else{
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>


<script type="text/javascript">
	function listarServicos(id){
	
		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){

				$("#listar_servicos").html(result);
			}
		});
	}
</script>




<script type="text/javascript">
			$("#form-servico").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				 $('#mensagem_servico').text('Salvando...')
    				$('#btn_salvar_servico').hide();

				$.ajax({
					url: 'paginas/' + pag + "/salvar_venda.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {

						$('#mensagem_servico').text('');
						$('#mensagem_servico').removeClass()
						if (mensagem.trim() == "Salvo com Sucesso") { 
						  $('#btn-fechar-servico').click();                		
               			 $('#mensagem_servico').text('') 
					} else {
						$('#mensagem_servico').addClass('text-danger')
						$('#mensagem_servico').text(mensagem)
					}

					$('#btn_salvar_servico').show();

				},

				cache: false,
				contentType: false,
				processData: false,

			});

			});
		</script>



		<script type="text/javascript">
	function abaterValor(){		

		var valor = $("#valor_serv").val(); 
		var valor_rest = $("#valor_serv_agd_restante").val();
		var desconto = $("#desconto").val(); 
		var valor_neutro = $("#valor_serv_neutro").val(); 

		if(valor == ""){
			valor = 0;
		} 

		if(valor_rest == ""){
			valor_rest = 0;
		} 

		if(desconto == ""){
			desconto = 0;
		} 

		var total = parseFloat(valor_neutro) - parseFloat(valor_rest) - parseFloat(desconto);
		$('#valor_serv').val(total.toFixed(2));

	}
</script>