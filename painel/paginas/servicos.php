<?php 
$pag = 'servicos';

//verificar se ele tem a permissão de estar nessa página
if(@$servicos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
 ?>

<div class="main-page margin-mobile">    
<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Serviço</a>



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

<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>


<input type="hidden" id="ids">

</div>

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
			<form id="form">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Serviço" required>							
						</div>

						<div class="col-md-3">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor Serviço" required>							
						</div>

						<div class="col-md-3">						
								<label>Comissão <?php echo $tipo_comissao ?></label>
								<input type="text" class="form-control" id="comissao" name="comissao" placeholder="Comissão se Houver" >							
						</div>					

						
					</div>


					<div class="row">
						

						<div class="col-md-12">						
								<label>Descrição</label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição se Houver" >							
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







<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



