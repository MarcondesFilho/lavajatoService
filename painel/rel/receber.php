<?php 
// Inclui o arquivo de conexão com o banco de dados
require_once("../../conexao.php");

// Inclui o arquivo de formatação de datas
require_once("data_formatada.php");

// Recebe os parâmetros via GET
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$pago = $_GET['pago'];
$tipo_data = $_GET['tipo_data'];

// Formata as datas para o formato dd/mm/yyyy
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

// Define o texto para o filtro de pagamento
$texto_pago = "";
if ($pago == 'Sim') {
    $texto_pago = ' PAGAS';
} elseif ($pago == 'Não') {
    $texto_pago = ' PENDENTES';
} elseif ($pago == 'Vencidas') {
    $texto_pago = ' VENCIDAS';
}

// Define o tipo de data
if ($tipo_data == "") {
    $tipo_data = "vencimento";
}

$texto_tipo_data = "";
if ($tipo_data == 'data_lanc') {
    $texto_tipo_data = 'Data de Lançamento';
} elseif ($tipo_data == 'data_pgto') {
    $texto_tipo_data = 'Data de Pagamento';
} elseif ($tipo_data == 'vencimento') {
    $texto_tipo_data = 'Data de Vencimento';
}

// Define o intervalo de datas
$datas = $dataInicial == $dataFinal ? $dataInicialF : $dataInicialF . ' à ' . $dataFinalF;

// Texto do filtro para exibição
$texto_filtro = $texto_tipo_data . ' : ' . $datas;

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
<?php if (isset($marca_dagua) && $marca_dagua == 'Sim') { ?>
    <img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">    
<?php } ?>

<div id="header">
    <div style="border-style: solid; font-size: 10px; height: 50px;">
        <table style="width: 100%; border: 0px solid #ccc;">
            <tr>
                <td style="border: 1px solid #000; width: 7%; text-align: left;">
                    <img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="180px">
                </td>
                <td style="width: 30%; text-align: left; font-size: 13px;">
                    <!-- Espaço em branco -->
                </td>
                <td style="width: 1%; text-align: center; font-size: 13px;">
                    <!-- Espaço em branco -->
                </td>
                <td style="width: 47%; text-align: right; font-size: 9px; padding-right: 10px;">
                    <b><big>RELATÓRIO DE CONTAS À RECEBER <?php echo $texto_pago ?></big></b>
                    <br>FILTRO: <?php echo mb_strtoupper($texto_filtro) ?>
                    <br> <?php echo mb_strtoupper($data_hoje) ?>
                </td>
            </tr>        
        </table>
    </div>
<br>
    <table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 8px; margin-bottom:10px; width: 100%; table-layout: fixed;">
        <thead>
            <tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
                <td style="width:22%">DESCRIÇÃO</td>
                <td style="width:10%">VALOR</td>
                <td style="width:17%">CLIENTE</td>
                <td style="width:12%">VENCIMENTO</td>
                <td style="width:12%">PAGAMENTO</td>
                <td style="width:15%">FORMA PGTO</td>    
                <td style="width:12%">FREQUÊNCIA</td>        
            </tr>
        </thead>
    </table>
</div>

<div id="footer" class="row">
    <hr style="margin-bottom: 0;">
    <table style="width:100%;">
        <tr style="width:100%;">
            <td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
            <td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
        </tr>
    </table>
</div>

<div id="content" style="margin-top: 0;">
    <table style="width: 100%; table-layout: fixed; font-size:7px; text-transform: uppercase;">
        <thead>
            <tbody>
                <?php

$total_valor = 0;
$total_valorF = 0;
$total_pendentes = 0;
$total_pendentesF = 0;
$total_pagas = 0;
$total_pagasF = 0;
$pendentes = 0;
$pagas = 0;

// Consulta ao banco de dados baseada no filtro de pagamento
if ($pago == 'Vencidas') {
    $query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' order by id desc");
} else {
    $query = $pdo->query("SELECT * from receber where $tipo_data >= '$dataInicial' and $tipo_data <= '$dataFinal' and pago LIKE '%$pago%' order by id desc");
}

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = count($res);
if ($linhas > 0) {
    for ($i=0; $i<$linhas; $i++) {
        // Atribuição dos valores da consulta
        $id = $res[$i]['id'];
        $descricao = $res[$i]['descricao'];
        $cliente = $res[$i]['cliente'];
        $valor = $res[$i]['valor'];
        $vencimento = $res[$i]['vencimento'];
        $data_pgto = $res[$i]['data_pgto'];
        $data_lanc = $res[$i]['data_lanc'];
        $forma_pgto = $res[$i]['forma_pgto'];
        $frequencia = $res[$i]['frequencia'];
        $obs = $res[$i]['obs'];
        $arquivo = $res[$i]['arquivo'];
        $referencia = $res[$i]['referencia'];
        $id_ref = $res[$i]['id_ref'];
        $multa = $res[$i]['multa'];
        $juros = $res[$i]['juros'];
        $desconto = $res[$i]['desconto'];
        $taxa = $res[$i]['taxa'];
        $subtotal = $res[$i]['subtotal'];
        $usuario_lanc = $res[$i]['usuario_lanc'];
        $usuario_pgto = $res[$i]['usuario_pgto'];
        $pago = $res[$i]['pago'];

        // Formatação das datas
        $vencimentoF = implode('/', array_reverse(explode('-', $vencimento)));
        $data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));
        $data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));

        // Formatação dos valores
        $valorF = number_format($valor, 2, ',', '.');
        $multaF = number_format($multa, 2, ',', '.');
        $jurosF = number_format($juros, 2, ',', '.');
        $descontoF = number_format($desconto, 2, ',', '.');
        $taxaF = number_format($taxa, 2, ',', '.');
        $subtotalF = number_format($subtotal, 2, ',', '.');

        // Define o valor final com base no status de pagamento
        $valor_finalF = $pago == "Sim" ? number_format($subtotal, 2, ',', '.') : number_format($valor, 2, ',', '.');

        // Consulta o nome do usuário que lançou a conta
        $query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_usu_lanc = count($res2) > 0 ? $res2[0]['nome'] : 'Sem Usuário';

        // Consulta o nome do usuário que efetuou o pagamento
        $query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_pgto'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_usu_pgto = count($res2) > 0 ? $res2[0]['nome'] : 'Sem Usuário';

        // Consulta o nome da frequência
        $query2 = $pdo->query("SELECT * FROM frequencias where dias = '$frequencia'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_frequencia = count($res2) > 0 ? $res2[0]['frequencia'] : 'Sem Registro';

        // Consulta o nome da forma de pagamento
        $query2 = $pdo->query("SELECT * FROM formas_pgto where id = '$forma_pgto'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_pgto = count($res2) > 0 ? $res2[0]['nome'] : 'Sem Registro';

        // Consulta o nome do cliente
        $query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_cliente = count($res2) > 0 ? $res2[0]['nome'] : 'Sem Registro';

        // Atualiza os totais e classes de status
        if ($pago == 'Sim') {
            $classe_pago = 'verde.jpg';
            $pagas += 1;
            $total_pagas += $subtotal;
        } else {
            $classe_pago = 'vermelho.jpg';
            $pendentes += 1;
            $total_pendentes += $valor;
        }    

        // Cálculo de multa e juros por atraso
        $valor_multa = 0;
        $valor_juros = 0;
        $classe_venc = '';
        if (strtotime($vencimento) < strtotime($data_hoje)) {
            $classe_venc = 'text-danger';
            $valor_multa = $multa_atraso;

            // Pega a quantidade de dias que o pagamento está atrasado
            $dif = strtotime($data_hoje) - strtotime($vencimento);
            $dias_vencidos = floor($dif / (60*60*24));

            $valor_juros = ($valor * $juros_atraso / 100) * $dias_vencidos;
        }

        // Formatação dos totais
        $total_pendentesF = number_format($total_pendentes, 2, ',', '.');
        $total_pagasF = number_format($total_pagas, 2, ',', '.');

        // Pegar resíduos da conta
        $total_resid = 0;
        $valor_com_residuos = 0;
        $query2 = $pdo->query("SELECT * FROM receber WHERE id_ref = '$id' and residuo = 'Sim'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        if (count($res2) > 0) {
            $descricao = '(Resíduo) - ' . $descricao;

            for ($i2=0; $i2 < count($res2); $i2++) {
                $id_res = $res2[$i2]['id'];
                $valor_resid = $res2[$i2]['valor'];
                $total_resid += $valor_resid - $res2[$i2]['desconto'];
            }

            $valor_com_residuos = $valor + $total_resid;
        }
        if ($valor_com_residuos > 0) {
            $vlr_antigo_conta = '('.$valor_com_residuos.')';
            $descricao_link = '';
            $descricao_texto = 'd-none';
        } else {
            $vlr_antigo_conta = '';
            $descricao_link = 'd-none';
            $descricao_texto = '';
        }
        ?>

        <!-- Exibição dos dados na tabela -->
        <tr>
            <td style="width:22%">
                <img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/<?php echo $classe_pago ?>" width="8px">
                <?php echo $descricao ?>
            </td>
            <td style="width:10%">R$ <?php echo $valor_finalF ?></td>
            <td style="width:17%"><?php echo $nome_cliente ?></td>
            <td style="width:12%"><?php echo $vencimentoF ?></td>
            <td style="width:12%"><?php echo $data_pgtoF ?></td>
            <td style="width:12%"><?php echo $nome_pgto ?></td>
            <td style="width:12%"><?php echo $nome_frequencia ?></td>
        </tr>
        <?php } } ?>
            </tbody>
        </thead>
    </table>
</div>

<hr>
<table>
    <thead>
        <tbody>
            <tr>
                <td style="font-size: 10px; width:300px; text-align: right;"></td>
                <td style="font-size: 10px; width:70px; text-align: right;"><b>Pendentes: <span style="color:red"><?php echo $pendentes ?></span></b></td>
                <td style="font-size: 10px; width:70px; text-align: right;"><b>Pagas: <span style="color:green"><?php echo $pagas ?></span></b></td>
                <td style="font-size: 10px; width:140px; text-align: right;"><b>Pendentes: <span style="color:red">R$ <?php echo $total_pendentesF ?></span></b></td>
                <td style="font-size: 10px; width:120px; text-align: right;"><b>Pagas: <span style="color:green">R$ <?php echo $total_pagasF ?></span></b></td>
            </tr>
        </tbody>
    </thead>
</table>

</body>
</html>
