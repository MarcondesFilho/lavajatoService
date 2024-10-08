<?php 
if(@$home == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
    exit();
}

require_once(__DIR__ . "/../../conexao.php");

// Definindo o intervalo de datas
$dataAtual = date('Y-m-d');
$dataInicialMensal = date('Y-m-01');
$dataFinalMensal = date('Y-m-t');
$dataInicialSemanal = date('Y-m-d', strtotime('monday this week'));
$dataFinalSemanal = date('Y-m-d', strtotime('sunday this week'));
$dataInicialAnual = date('Y-01-01');
$dataFinalAnual = date('Y-12-31');

// Inicializando os valores padrão para o filtro mensal
$dataInicial = $dataInicialMensal;
$dataFinal = $dataFinalMensal;
$periodoTexto = "Mensal";
$filtro = "mensal";

if (isset($_GET['filtro']) && $_GET['filtro'] == 'semanal') {
    $dataInicial = $dataInicialSemanal;
    $dataFinal = $dataFinalSemanal;
    $periodoTexto = "Semanal";
	$filtro = "semanal";
} elseif (isset($_GET['filtro']) && $_GET['filtro'] == 'anual') {
    $dataInicial = $dataInicialAnual;
    $dataFinal = $dataFinalAnual;
    $periodoTexto = "Anual";
	$filtro = "anual";
}

// Query para obter os dados financeiros gerais
$query = $pdo->query("
    SELECT 
        (SELECT SUM(valor) FROM receber WHERE data_pgto >= '$dataInicialAnual' AND data_pgto <= '$dataFinalAnual' AND pago = 'Sim') AS receita_bruta,
        (SELECT SUM(valor) FROM pagar WHERE data_pgto >= '$dataInicialAnual' AND data_pgto <= '$dataFinalAnual' AND pago = 'Sim') AS despesas,
        (SELECT COUNT(*) FROM clientes WHERE id IN (SELECT DISTINCT cliente FROM itens_servicos WHERE data >= DATE_SUB(NOW(), INTERVAL 1 MONTH))) AS clientes_ativos
");

$resultado = $query->fetch(PDO::FETCH_ASSOC);
$receita_bruta = $resultado['receita_bruta'] ?: 0;
$despesas = $resultado['despesas'] ?: 0;
$clientes_ativos = $resultado['clientes_ativos'] ?: 0;
$receita_liquida = $receita_bruta - $despesas;

// Query para obter o fluxo de caixa e outros dados filtrados
$query_filtro = $pdo->query("
    SELECT 
        (SELECT SUM(valor) FROM receber WHERE data_pgto >= '$dataInicial' AND data_pgto <= '$dataFinal' AND pago = 'Sim') AS entradas,
        (SELECT SUM(valor) FROM pagar WHERE data_pgto >= '$dataInicial' AND data_pgto <= '$dataFinal' AND pago = 'Sim') AS saidas,
        (SELECT COUNT(*) FROM itens_servicos WHERE data >= '$dataInicial' AND data <= '$dataFinal') AS novos_servicos,
        (SELECT COUNT(*) FROM clientes WHERE data_cad >= '$dataInicial' AND data_cad <= '$dataFinal') AS novos_clientes
");

$resultado_filtro = $query_filtro->fetch(PDO::FETCH_ASSOC);
$entradas = $resultado_filtro['entradas'] ?: 0;
$saidas = $resultado_filtro['saidas'] ?: 0;
$fluxo_caixa = $entradas - $saidas;
$novos_servicos = $resultado_filtro['novos_servicos'] ?: 0;
$novos_clientes = $resultado_filtro['novos_clientes'] ?: 0;

// Query para obter a quantidade de serviços por dia na semana atual
if ($filtro == 'anual') {
    $query_grafico = $pdo->query("
        SELECT 
            DATE_FORMAT(data, '%Y-%m') AS periodo, 
            COUNT(*) AS quantidade 
        FROM itens_servicos 
        WHERE data >= '$dataInicial' AND data <= '$dataFinal' 
        GROUP BY periodo 
        ORDER BY periodo
    ");
} elseif ($filtro == 'mensal') {
    $query_grafico = $pdo->query("
        SELECT 
            CONCAT('Semana ', WEEK(data, 1) - WEEK(DATE_SUB(data, INTERVAL DAYOFMONTH(data) - 1 DAY), 1) + 1) AS periodo, 
            COUNT(*) AS quantidade 
        FROM itens_servicos 
        WHERE data >= '$dataInicial' AND data <= '$dataFinal' 
        GROUP BY periodo 
        ORDER BY periodo
    ");
} else {
    $query_grafico = $pdo->query("
        SELECT 
            DATE_FORMAT(data, '%W') AS periodo, 
            COUNT(*) AS quantidade 
        FROM itens_servicos 
        WHERE data >= '$dataInicial' AND data <= '$dataFinal' 
        GROUP BY periodo 
        ORDER BY FIELD(periodo, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')
    ");
}

$dados_grafico = $query_grafico->fetchAll(PDO::FETCH_ASSOC);

// Traduzindo os dias da semana
$diasSemana = ['Sunday' => 'Domingo', 'Monday' => 'Segunda-feira', 'Tuesday' => 'Terça-feira', 'Wednesday' => 'Quarta-feira', 'Thursday' => 'Quinta-feira', 'Friday' => 'Sexta-feira', 'Saturday' => 'Sábado'];
$meses = ['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'];

$dados_grafico = array_map(function($dado) use ($diasSemana, $meses, $filtro) {
    if ($filtro == 'semanal') {
        $dado['periodo'] = $diasSemana[$dado['periodo']];
    } elseif ($filtro == 'anual') {
        $anoMes = explode('-', $dado['periodo']);
        $mes = $anoMes[1];
        $ano = $anoMes[0];
        $dado['periodo'] = $meses[$mes] . '/' . $ano;
    }
    return $dado;
}, $dados_grafico);
?>

<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="main-page margin-mobile">
	<?php if($ativo_sistema == ''): ?>
		<div style="background: #ffc341; color:#3e3e3e; padding:10px; font-size:14px; margin-bottom:10px">
			<div><i class="fa fa-info-circle"></i> <b>Aviso: </b> Prezado Cliente, não identificamos o pagamento de sua última mensalidade, entre em contato conosco o mais rápido possivel para regularizar o pagamento, caso contário seu acesso ao sistema será desativado.</div>
		</div>
	<?php endif; ?>
	
	<div class="text-center mb-4">
		<a href="?filtro=semanal" class="btn btn-default" <?php echo $_GET['filtro'] == 'semanal' ? "style='background-color: #1767bd;color: #fff;border: 1px solid #000000;'" : '' ?> id="btn-semanal">Semanal</a>
		<a href="?filtro=mensal" class="btn btn-default" <?php echo $_GET['filtro'] == 'mensal' ? "style='background-color: #1767bd;color: #fff;border: 1px solid #000000;'" : '' ?> id="btn-mensal">Mensal</a>
		<a href="?filtro=anual" class="btn btn-default" <?php echo $_GET['filtro'] == 'anual' ? "style='background-color: #1767bd;color: #fff;border: 1px solid #000000;'" : '' ?> id="btn-anual">Anual</a>
    </div>

	<div class="col_2">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
					<i class="pull-left fa fa-dollar icon-rounded"></i>
					<div class="stats">
						<h5><strong>R$<?php echo number_format($fluxo_caixa, 2, ',', '.'); ?></strong></h5>
                        <span>Fluxo de Caixa</span>
					</div>
			</div>
		</div>
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
					<i class="pull-left fa fa-money user2 icon-rounded"></i>
					<div class="stats">
						<h5><strong>R$<?php echo number_format($receita_bruta, 2, ',', '.'); ?></strong></h5>
						<span>Receita Bruta</span>
					</div>
			</div>
		</div>
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
					<i class="pull-left fa fa-money dollar2 icon-rounded"></i>
					<div class="stats">
						<h5><strong>R$<?php echo number_format($receita_liquida, 2, ',', '.'); ?></strong></h5>
						<span>Receita Líquida</span>
					</div>
			</div>
		</div>
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
					<i class="pull-left fa fa-pie-chart user1 icon-rounded"></i>
					<div class="stats">
						<h5><strong>R$<?php echo number_format($despesas, 2, ',', '.'); ?></strong></h5>
						<span>Despesas</span>
					</div>
			</div>
		</div>
		<div class="col-md-3 widget widget2">
			<div class="r3_counter_box">
					<i class="pull-left fa fa-users dollar2 icon-rounded"></i>
					<div class="stats">
						<h5><strong><?php echo $clientes_ativos; ?></strong></h5>
						<span>Clientes</span>
					</div>
			</div>
		</div>
		<div class="clearfix"> </div>
	</div>

	<div class="row-one widgettable">
		<div class="col-md-8 content-top-2 card">
			<div class="agileinfo-cdr altura_grafico">
				<div class="card-header">
					<h3>Vendas Semanal</h3>
				</div>
					<canvas id="graficoServicos" style="width: 100%; height: 350px;"></canvas>
			</div>
		</div>
		<div class="col-md-4 stat">
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Serviços</h5>
					<label><?php echo $novos_servicos; ?>+</label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-1" class="pie-title-center" data-percent="45"> <span class="pie-value"></span> </div>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Novos Clientes</h5>
					<label><?php echo $novos_clientes; ?>+</label>
				</div>
				<div class="col-md-6 top-content1">
					<div id="demo-pie-2" class="pie-title-center" data-percent="75">
						<span class="pie-value"></span>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Visitors</h5>
					<label>12+</label>
				</div>
				<div class="col-md-6 top-content1">
					<div id="demo-pie-3" class="pie-title-center" data-percent="90"> 
						<span class="pie-value"></span> 
					</div>
				</div>
				<div class="clearfix"> </div>
				</div>
			</div>
		</div>

		<div class="clearfix"> </div>
	</div>


</div>
    
<canvas id="graficoServicos"></canvas>
<script>
	var ctx = document.getElementById('graficoServicos').getContext('2d');
	var dadosGrafico = <?php echo json_encode($dados_grafico); ?>;
	var periodos = dadosGrafico.map(dado => dado.periodo);
	var quantidades = dadosGrafico.map(dado => dado.quantidade);

	var graficoServicos = new Chart(ctx, {
		type: 'line',
		data: {
			labels: periodos,
			datasets: [{
				label: 'Serviços por <?php echo $periodoTexto; ?>',
				data: quantidades,
				fill: true, // Preenche a área abaixo da linha
				backgroundColor: 'rgba(75, 192, 192, 0.2)', // Cor de preenchimento
				borderColor: 'rgba(75, 192, 192, 1)', // Cor da borda
				borderWidth: 1,
				tension: 0.4 // Curvatura da linha
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			},
			elements: {
				line: {
					tension: 0.4 // Curvatura da linha
				}
			}
		}
	});
</script>