<?php 
require_once("../../../conexao.php");
$ultimo = @$_POST['ultimo'];

echo '<select name="cliente" id="cliente" class="sel3" style="width:100%; height:35px">';
if($ultimo == ""){
	echo '<option value="0">Selecione um Cliente</option>';
}								
	$query = $pdo->query("SELECT * from clientes order by id desc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){
			echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
		}
	}
								
echo '</select>';
 ?>

 	<script type="text/javascript">
		$(document).ready(function() {
			
			$('.sel3').select2({
				dropdownParent: $('#modalForm')
			});

			
		});
	</script>