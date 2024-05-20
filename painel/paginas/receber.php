<?php 
$pag = 'receber';

if(@$receber == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="main-page margin-mobile">

	<div class="row" >	
		<div class="col-md-4" style="margin-bottom: 5px">
			<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Receber</a>

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


			<li class="dropdown head-dpdn2" style="display: inline-block;">		
				<a href="#" data-toggle="dropdown"  class="btn btn-success dropdown-toggle" id="btn-baixar" style="display:none" onclick="valorBaixar()"><span class="fa fa-check-square"></span> Baixar</a>

				<ul class="dropdown-menu">
					<li>
						<div class="notification_desc2">
							<p>Baixar contas Selecionadas? <a href="#" onclick="deletarSelBaixar()"><span class="text-verde">Sim</span></a></p>
							<p><b>Total das Contas</b> R$ <span id="total_contas"></span></p>
						</div>
					</li>										
				</ul>
			</li>	
		</div>

		<form action="rel/receber_class.php" target="_blank" method="POST">
		<div class="col-md-3" >
			<input type="date" name="dataInicial" id="dataInicial" style="height:31px; width:49%; font-size: 13px;" value="<?php echo $data_inicio_mes ?>" onchange="buscar()">

			<input type="date" name="dataFinal" id="dataFinal" style="height:31px; width:49%; font-size: 13px" value="<?php echo $data_final_mes ?>" onchange="buscar()">	
		</div>

		<div class="col-md-1 padding_zero">
			<select class="form-control" name="pago" id="pago" style="height: 31px" onchange="buscar()">
				<option value="">Todas</option>
				<option value="Sim">Pagas</option>
				<option value="Não">Pendentes</option>
				<option value="Vencidas">Vencidas</option>
			</select>
		</div>

		<div class="col-md-3 esc" style="margin-top: 5px">
			<span style="font-size: 14px; border:1px solid #6092a8; padding:5px; ">
				<a href="#" onclick="trocarData('mes')">Mês</a> / 
				<a href="#" onclick="trocarData('hoje')">Hoje</a> / 
				<a href="#" onclick="trocarData('ontem')">Ontem</a> /
				<a href="#" onclick="trocarData('amanha')">Amanhã</a> 
			</span>
		</div>

		<input type="hidden" name="tipo_data" id="tipo_data">

		<div class="col-md-1 botao_rel">
			<button type="submit" class="btn btn-danger" title="Gerar Relatório"><i class="fa fa-file-pdf-o"></i></button>
		</div>
		</form>
		
	</div>	





	<div class="bs-example widget-shadow" style="padding:15px" id="listar">

	</div>

</div>

<input type="hidden" id="ids">

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">


					<div class="row">
						<div class="col-md-5">							
							<label>Descrição</label>
							<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição" >							
						</div>

						<div class="col-md-2">							
							<label>Valor</label>
							<input type="text" class="form-control" id="valor" name="valor" placeholder="" required>							
						</div>

						<div class="col-md-5">							
							<label>Cliente</label>
								<select name="cliente" id="cliente" class="sel2" style="width:100%; height:35px">
								<option value="0">Selecione um Cliente</option>
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


					<div class="row">

						
						

						<div class="col-md-3">							
							<label>Vencimento</label>
							<input type="date" name="vencimento" id="vencimento" value="<?php echo $data_atual ?>" class="form-control">							
						</div>


						<div class="col-md-3">							
							<label>Pago Em</label>
							<input type="date" name="data_pgto" id="data_pgto" value="" class="form-control">							
						</div>


						<div class="col-md-3">							
							<label>Forma Pgto</label>
							<select name="forma_pgto" id="forma_pgto" class="form-control">
								<?php 
								$query = $pdo->query("SELECT * from formas_pgto order by id asc");
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);
								if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
										echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
									}
								}else{
									echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
								}
								?>	
							</select>								
						</div>

						<div class="col-md-3">							
							<label>Frequência</label>
							<select name="frequencia" id="frequencia" class="form-control">
								<?php 
								$query = $pdo->query("SELECT * from frequencias order by id asc");
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);
								if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
										echo '<option value="'.$res[$i]['dias'].'">'.$res[$i]['frequencia'].'</option>';
									}
								}else{
									echo '<option value="0">Cadastre uma Forma de Pagamento</option>';
								}
								?>	
							</select>								
						</div>


						
					</div>

					<div class="row">
						<div class="col-md-6">							
							<label>Observações</label>
							<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações" >							
						</div>

						<div class="col-md-4">							
							<label>Arquivo</label>
							<input type="file" class="form-control" id="arquivo" name="foto" onchange="carregarImg()">							
						</div>

						<div class="col-md-2">								
							<img width="80px" id="target">						
							
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
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span> / R$ <span id="valor_dados"></span></h4>
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
						<span><b>Vencimento: </b></span><span id="vencimento_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Pagamento: </b></span><span id="data_pgto_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Forma PGTO: </b></span><span id="nome_pgto_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Frequencia: </b></span><span id="frequencia_dados"></span>
					</div>



					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Multa: </b></span><span id="multa_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Júros </b></span><span id="juros_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Desconto: </b></span><span id="desconto_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Taxa </b></span><span id="taxa_dados"></span>
					</div>



					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Subtotal </b></span><span id="total_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Pago </b></span><span id="pago_dados"></span>
					</div>


					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Lançador Por </b></span><span id="usu_lanc_dados"></span>
					</div>

					<div class="col-md-6" style="margin-bottom: 5px">
						<span><b>Baixa </b></span><span id="usu_pgto_dados"></span>
					</div>




					<div class="col-md-12" style="margin-bottom: 5px">
						<span><b>OBS: </b></span><span id="obs_dados"></span>
					</div>


					<div class="col-md-12" style="margin-bottom: 5px">
						<div align="center">
							<a href="" target="_blank" id="target_link_dados">
								<img src="" id="target_dados" width="200px">
							</a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>






<div class="modal fade" id="modalParcelar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="tituloModal">Parcelar Conta: <span id="nome-parcelar"> </span></h4>
				<button id="btn-fechar-parcelar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form-parcelar">
				<div class="modal-body">


					<div class="row">
						<div class="col-md-3">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Valor</label>
								<input type="text" class="form-control" name="valor-parcelar"  id="valor-parcelar"  readonly>
							</div>
						</div>

						<div class="col-md-2">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Parcelas</label>
								<input type="number" class="form-control" name="qtd-parcelar"  id="qtd-parcelar"  required>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group"> 
								<label>Frequência Parcelas</label> 
								<select class="form-control sel3" name="frequencia" id="frequencia-parcelar" required style="width:100%;">
									
									<?php 
									$query = $pdo->query("SELECT * FROM frequencias order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									for($i=0; $i < @count($res); $i++){
										foreach ($res[$i] as $key => $value){	}
											$id_item = $res[$i]['id'];
										$nome_item = $res[$i]['frequencia'];
										$dias = $res[$i]['dias'];

										if($nome_item != 'Uma Vez' and $nome_item != 'Única' and $nome_item != 'Nenhuma'){

											?>
											<option <?php if($nome_item == 'Mensal'){ ?> selected <?php } ?> value="<?php echo $dias ?>"><?php echo $nome_item ?></option>

										<?php } } ?>


									</select>
								</div>
							</div>

							<div class="col-md-3" style="margin-top:20px">						 
								<button type="submit" class="btn btn-primary">Parcelar</button>
							</div>

						</div>	



						<br>
						<input type="hidden" name="id-parcelar" id="id-parcelar"> 
						<input type="hidden" name="nome-parcelar" id="nome-input-parcelar"> 
						<small><div id="mensagem-parcelar" align="center" class="mt-3"></div></small>					

					</div>

					<div class="modal-footer">

					</div>

				</form>

			</div>
		</div>
	</div>






	<!-- Modal -->
	<div class="modal fade" id="modalBaixar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Baixar Conta: <span id="descricao-baixar"> </span></h4>
					<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form-baixar" method="post">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Valor <small class="text-muted">(Total ou Parcial)</small></label>
									<input onkeyup="totalizar()" type="text" class="form-control" name="valor-baixar"  id="valor-baixar" required>
								</div>
							</div>


							<div class="col-md-6">
								<div class="form-group"> 
									<label>Forma PGTO</label> 
									<select class="form-control" name="saida-baixar" id="saida-baixar" required onchange="calcularTaxa()">	
										<?php 
										$query = $pdo->query("SELECT * FROM formas_pgto order by id asc");
										$res = $query->fetchAll(PDO::FETCH_ASSOC);
										for($i=0; $i < @count($res); $i++){
											foreach ($res[$i] as $key => $value){}

												?>	
											<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>

										<?php } ?>

									</select>
								</div>
							</div>

						</div>	


						<div class="row">


							<div class="col-md-3">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Multa em R$</label>
									<input onkeyup="totalizar()" type="text" class="form-control" name="valor-multa"  id="valor-multa" placeholder="Ex 15.00" value="0">
								</div>
							</div>

							<div class="col-md-3">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Júros em R$</label>
									<input onkeyup="totalizar()" type="text" class="form-control" name="valor-juros"  id="valor-juros" placeholder="Ex 0.15" value="0">
								</div>
							</div>

							<div class="col-md-3">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Desconto em R$</label>
									<input onkeyup="totalizar()" type="text" class="form-control" name="valor-desconto"  id="valor-desconto" placeholder="Ex 15.00" value="0" >
								</div>
							</div>



							<div class="col-md-3">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Taxa PGTO</label>
									<input onkeyup="totalizar()" type="text" class="form-control" name="valor-taxa"  id="valor-taxa" placeholder="" value="" >
								</div>
							</div>

						</div>


						<div class="row">

							<div class="col-md-6">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">Data da Baixa</label>
									<input type="date" class="form-control" name="data-baixar"  id="data-baixar" value="<?php echo date('Y-m-d') ?>" >
								</div>
							</div>


							<div class="col-md-6">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">SubTotal</label>
									<input type="text" class="form-control" name="subtotal"  id="subtotal" readonly>
								</div>	
							</div>
						</div>




						<small><div id="mensagem-baixar" align="center"></div></small>

						<input type="hidden" class="form-control" name="id-baixar"  id="id-baixar">


					</div>
					<div class="modal-footer">
						
						<button type="submit" class="btn btn-success">Baixar</button>
					</div>
				</form>
			</div>
		</div>
	</div>




	<!-- Modal -->
	<div class="modal fade" id="modalResiduos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Residuos da Conta</h4>
					<button id="btn-fechar-parcelar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<small><div id="listar-residuos"></div></small>

				</div>
				
			</div>
		</div>
	</div>




	<!-- Modal Arquivos -->
	<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome-arquivo"> </span></h4>
					<button id="btn-fechar-arquivos" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form-arquivos" method="post">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Arquivo</label> 
									<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
								</div>	
							</div>
							<div class="col-md-4" style="margin-top:-10px">	
								<div id="divImgArquivos">
									<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
								</div>					
							</div>




						</div>

						<div class="row" style="margin-top:-40px">
							<div class="col-md-8">
								<input type="text" class="form-control" name="nome-arq"  id="nome-arq" placeholder="Nome do Arquivo * " required>
							</div>

							<div class="col-md-4">										 
								<button type="submit" class="btn btn-primary">Inserir</button>
							</div>
						</div>

						<hr>

						<small><div id="listar-arquivos"></div></small>

						<br>
						<small><div align="center" id="mensagem-arquivo"></div></small>

						<input type="hidden" class="form-control" name="id-arquivo"  id="id-arquivo">


					</div>
				</form>
			</div>
		</div>



	<script type="text/javascript">var pag = "<?=$pag?>"</script>
	<script src="js/ajax.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			$('.sel2').select2({
				dropdownParent: $('#modalForm')
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
		function carregarImg() {
			var target = document.getElementById('target');
			var file = document.querySelector("#arquivo").files[0];

			var arquivo = file['name'];
			resultado = arquivo.split(".", 2);

			if(resultado[1] === 'pdf'){
				$('#target').attr('src', "images/pdf.png");
				return;
			}

			if(resultado[1] === 'rar' || resultado[1] === 'zip'){
				$('#target').attr('src', "images/rar.png");
				return;
			}

			if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
				$('#target').attr('src', "images/word.png");
				return;
			}


			if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
				$('#target').attr('src', "images/excel.png");
				return;
			}


			if(resultado[1] === 'xml'){
				$('#target').attr('src', "images/xml.png");
				return;
			}



			var reader = new FileReader();

			reader.onloadend = function () {
				target.src = reader.result;
			};

			if (file) {
				reader.readAsDataURL(file);

			} else {
				target.src = "";
			}
		}
	</script>


	<script type="text/javascript">
		function buscar(){
			dataInicial = $('#dataInicial').val();
			dataFinal = $('#dataFinal').val();
			pago = $('#pago').val();
			tipo_data = $('#tipo_data').val();

			listar(dataInicial, dataFinal, pago, tipo_data);

		}

		function trocarData(tipo){

			data_inicio_mes = "<?=$data_inicio_mes?>";
			data_final_mes = "<?=$data_final_mes?>";
			data_atual = "<?=$data_atual?>";
			data_ontem = "<?=$data_ontem?>";
			data_amanha = "<?=$data_amanha?>";		

			if(tipo == 'mes'){
				$('#dataInicial').val(data_inicio_mes);
				$('#dataFinal').val(data_final_mes);
			}

			if(tipo == 'hoje'){
				$('#dataInicial').val(data_atual);
				$('#dataFinal').val(data_atual);
			}

			if(tipo == 'amanha'){			
				$('#dataInicial').val(data_amanha);
				$('#dataFinal').val(data_amanha);
			}

			if(tipo == 'ontem'){
				$('#dataInicial').val(data_ontem);
				$('#dataFinal').val(data_ontem);
			}

			buscar();
		}

		function tipoData(tipo){
			$('#tipo_data').val(tipo);
			buscar();
		}




		function totalizar(){
			valor = $('#valor-baixar').val();
			desconto = $('#valor-desconto').val();
			juros = $('#valor-juros').val();
			multa = $('#valor-multa').val();
			taxa = $('#valor-taxa').val();

			valor = valor.replace(",", ".");
			desconto = desconto.replace(",", ".");
			juros = juros.replace(",", ".");
			multa = multa.replace(",", ".");
			taxa = taxa.replace(",", ".");

			if(valor == ""){
				valor = 0;
			}

			if(desconto == ""){
				desconto = 0;
			}

			if(juros == ""){
				juros = 0;
			}

			if(multa == ""){
				multa = 0;
			}

			if(taxa == ""){
				taxa = 0;
			}

			subtotal = parseFloat(valor) + parseFloat(juros) + parseFloat(taxa) + parseFloat(multa) - parseFloat(desconto);


			console.log(subtotal)

			$('#subtotal').val(subtotal);

		}

		function calcularTaxa(){
			pgto = $('#saida-baixar').val();
			valor = $('#valor-baixar').val();
			 $.ajax({
		        url: 'paginas/' + pag + "/calcular_taxa.php",
		        method: 'POST',
		        data: {valor, pgto},
		        dataType: "html",

		        success:function(result){		           
		            $('#valor-taxa').val(result);
		             totalizar();
		        }
		    });


		}
	</script>



	<script type="text/javascript">
			$("#form-baixar").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/' + pag + "/baixar.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem-baixar').text('');
						$('#mensagem-baixar').removeClass()
						if (mensagem.trim() == "Baixado com Sucesso") {                    
							$('#btn-fechar-baixar').click();
							buscar();
						} else {
							$('#mensagem-baixar').addClass('text-danger')
							$('#mensagem-baixar').text(mensagem)
						}

					},

					cache: false,
					contentType: false,
					processData: false,

				});

			});
		</script>



<script type="text/javascript">
			$("#form-parcelar").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/' + pag + "/parcelar.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem-parcelar').text('');
						$('#mensagem-parcelar').removeClass()
						if (mensagem.trim() == "Parcelado com Sucesso") {                    
							$('#btn-fechar-parcelar').click();
							buscar();
						} else {
							$('#mensagem-parcelar').addClass('text-danger')
							$('#mensagem-parcelar').text(mensagem)
						}

					},

					cache: false,
					contentType: false,
					processData: false,

				});

			});


			function valorBaixar(){
				var ids = $('#ids').val();
				
				 $.ajax({
			        url: 'paginas/' + pag + "/valor_baixar.php",
			        method: 'POST',
			        data: {ids},
			        dataType: "html",

			        success:function(result){
			            $("#total_contas").html(result);
			           
			        }
			    });
			}
		</script>



<script type="text/javascript">
			$("#form-arquivos").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/' + pag + "/arquivos.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem-arquivo').text('');
						$('#mensagem-arquivo').removeClass()
						if (mensagem.trim() == "Inserido com Sucesso") {                    
						//$('#btn-fechar-arquivos').click();
						$('#nome-arq').val('');
						$('#arquivo_conta').val('');
						$('#target-arquivos').attr('src','images/arquivos/sem-foto.png');
						listarArquivos();
					} else {
						$('#mensagem-arquivo').addClass('text-danger')
						$('#mensagem-arquivo').text(mensagem)
					}

				},

				cache: false,
				contentType: false,
				processData: false,

			});

			});
		</script>

		<script type="text/javascript">
			function listarArquivos(){
				var id = $('#id-arquivo').val();	
				$.ajax({
					url: 'paginas/' + pag + "/listar-arquivos.php",
					method: 'POST',
					data: {id},
					dataType: "text",

					success:function(result){
						$("#listar-arquivos").html(result);
					}
				});
			}

		</script>




<script type="text/javascript">
		function carregarImgArquivos() {
			var target = document.getElementById('target-arquivos');
			var file = document.querySelector("#arquivo_conta").files[0];

			var arquivo = file['name'];
			resultado = arquivo.split(".", 2);

			if(resultado[1] === 'pdf'){
				$('#target-arquivos').attr('src', "images/pdf.png");
				return;
			}

			if(resultado[1] === 'rar' || resultado[1] === 'zip'){
				$('#target-arquivos').attr('src', "images/rar.png");
				return;
			}

			if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
				$('#target-arquivos').attr('src', "images/word.png");
				return;
			}


			if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
				$('#target-arquivos').attr('src', "images/excel.png");
				return;
			}


			if(resultado[1] === 'xml'){
				$('#target-arquivos').attr('src', "images/xml.png");
				return;
			}



			var reader = new FileReader();

			reader.onloadend = function () {
				target.src = reader.result;
			};

			if (file) {
				reader.readAsDataURL(file);

			} else {
				target.src = "";
			}
		}
	</script>