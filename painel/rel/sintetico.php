<?php 
// Inclui o arquivo de conexão com o banco de dados e a função de formatação de data
include('../../conexao.php');
include('data_formatada.php');

// Recebe os parâmetros via GET
$filtro_data = $_GET['filtro_data'];
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$filtro_tipo = $_GET['filtro_tipo'];
$filtro_lancamento = $_GET['filtro_lancamento'];
$filtro_pendentes = $_GET['filtro_pendentes'];

// Formata as datas para o formato brasileiro (dd/mm/yyyy)
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

// Define os textos de filtro baseados nos valores recebidos
$filtro_tipoF = ($filtro_tipo == "receber") ? 'ENTRADAS / GANHOS' : 'SAÍDAS / DESPESAS';
$classe_entradas = ($filtro_tipo == "receber") ? 'green' : 'red';

$filtro_dataF = ($filtro_data == "data_lanc") ? 'DATA DE LANÇAMENTO' : (($filtro_data == "data_venc") ? 'DATA DE VENCIMENTO' : 'DATA DE PAGAMENTO');

$filtro_lancamentoF = '';
$filtro_tabela = '';
if($filtro_lancamento == 'fornecedor'){		
	$filtro_lancamentoF = 'Pagamentos Fornecedores';
	$filtro_tabela = 'fornecedores';		
} elseif($filtro_lancamento == 'funcionario'){		
	$filtro_lancamentoF = 'Pagamentos Funcionários';	
	$filtro_tabela = 'usuarios';		
}

$filtro_pendentesF = ($filtro_pendentes == "Não") ? 'PENDENTES' : (($filtro_pendentes == "Sim") ? 'PAGAS' : '');

// Define o texto de datas para o filtro
$datas = ($dataInicial == $dataFinal) ? $dataInicialF : $dataInicialF . ' à ' . $dataFinalF;
$texto_filtro = $filtro_dataF . ' : ' . $datas . ' ' . $filtro_pendentesF;
?>

<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content { margin-top: 0px; }
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after { content: counter(page, my-sec-counter); }
body { font-family: 'Tw Cen MT', sans-serif; }

.marca {
    position: fixed;
    left: 50;
    top: 100;
    width: 80%;
    opacity: 8%;
}
</style>
</head>
<body>
<?php if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
<?php } ?>

<div id="header">
    <div style="border-style: solid; font-size: 10px; height: 50px;">
        <table style="width: 100%; border: 0px solid #ccc;">
            <tr>
                <td style="border: 1px; solid #000; width: 7%; text-align: left;">
                    <img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="180px">
                </td>
                <td style="width: 30%; text-align: left; font-size: 13px;"></td>
                <td style="width: 1%; text-align: center; font-size: 13px;"></td>
                <td style="width: 47%; text-align: right; font-size: 9px; padding-right: 10px;">
                    <b><big>RELATÓRIO DE <?php echo mb_strtoupper($filtro_lancamentoF) ?></big></b><br>
                    <?php echo mb_strtoupper($texto_filtro) ?><br>
                    <?php echo mb_strtoupper($data_hoje) ?>
                </td>
            </tr>		
        </table>
    </div>
<br>
    <table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 9px; margin-bottom: 10px; width: 100%; table-layout: fixed;">
        <thead>
            <tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
                <td style="width:30%">NOME</td>
                <td style="width:10%">VALOR TOTAL</td>
                <td style="width:10%">PENDENTES</td>
                <td style="width:10%">PAGAS</td>
                <td style="width:10%">CONTAS</td>
                <td style="width:30%">CHAVE PIX</td>
            </tr>
        </thead>
    </table>
</div>

<div id="footer" class="row">
    <hr style="margin-bottom: 0;">
    <table style="width:100%;">
        <tr style="width:100%;">
            <td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
            <td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página</p></td>
        </tr>
    </table>
</div>

<div id="content" style="margin-top: 0;">
    <table style="width: 100%; table-layout: fixed; font-size: 9px; text-transform: uppercase;">
        <thead>
            <tbody>
                <?php
                $query9 = $pdo->query("SELECT * from $filtro_tabela order by nome asc");
                $res9 = $query9->fetchAll(PDO::FETCH_ASSOC);
                $linhas9 = count($res9);
                if($linhas9 > 0){
                    for($i9=0; $i9<$linhas9; $i9++){
                        $id_pessoa = $res9[$i9]['id'];
                        $nome_pessoa = $res9[$i9]['nome'];
                        $pix_pessoa = $res9[$i9]['pix'];

                        $total_valor = 0;
                        $total_valorF = 0;
                        $total_pendentes = 0;
                        $total_pendentesF = 0;
                        $total_pagas = 0;
                        $total_pagasF = 0;
                        $pendentes = 0;
                        $pagas = 0;

                        $query = $pdo->query("SELECT * from $filtro_tipo where $filtro_data >= '$dataInicial' and $filtro_data <= '$dataFinal' and pago LIKE '%$filtro_pendentes%' and $filtro_lancamento = '$id_pessoa' order by $filtro_data asc");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        $linhas = count($res);
                        if($linhas > 0){
                            for($i=0; $i<$linhas; $i++){
                                $id = $res[$i]['id'];
                                $descricao = $res[$i]['descricao'];
                                $valor = $res[$i]['valor'];
                                $data_lanc = $res[$i]['data_lanc'];
                                $data_venc = $res[$i]['vencimento'];
                                $data_pgto = $res[$i]['data_pgto'];
                                $usuario_lanc = $res[$i]['usuario_lanc'];
                                $usuario_pgto = $res[$i]['usuario_pgto'];
                                $arquivo = $res[$i]['arquivo'];
                                $pago = $res[$i]['pago'];
                                $obs = $res[$i]['obs'];
                                $referencia = $res[$i]['referencia'];

                                $data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
                                $data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
                                $data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
                                $valorF = number_format($valor, 2, ',', '.');

                                if($pago == 'Sim'){
                                    $total_pagas += $valor;
                                    $pagas += 1;
                                } else {
                                    $total_pendentes += $valor;
                                    $pendentes += 1;
                                }

                                $total_valor += $valor;
                                $total_valorF = number_format($total_valor, 2, ',', '.');
                                $total_pagasF = number_format($total_pagas, 2, ',', '.');
                                $total_pendentesF = number_format($total_pendentes, 2, ',', '.');

                                if($data_pgtoF == '00/00/0000'){
                                    $data_pgtoF = 'Pendente';
                                }
                            }
                        } else {
                            // Continue para pular para o próximo
                            continue;
                        }
                ?>

                <tr>
                    <td style="width:30%"><?php echo $nome_pessoa ?></td>
                    <td style="width:10%">R$ <?php echo $total_valorF ?></td>
                    <td style="width:10%; color:red">R$ <?php echo $total_pendentesF ?></td>
                    <td style="width:10%; color:green">R$ <?php echo $total_pagasF ?></td>
                    <td style="width:10%"><?php echo $linhas ?></td>
                    <td style="width:30%"><?php echo $pix_pessoa ?></td>
                </tr>

                <?php } } ?>
            </tbody>
        </thead>
    </table>
</div>
<hr>
</body>
</html>
