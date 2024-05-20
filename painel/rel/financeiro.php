<?php 
// Inclui o arquivo de conexão com o banco de dados
include('../../conexao.php');

// Inclui um arquivo que provavelmente formata datas
include('data_formatada.php');

// Recebe parâmetros via GET
$filtro_data = $_GET['filtro_data'];
$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$filtro_tipo = $_GET['filtro_tipo'];
$filtro_lancamento = $_GET['filtro_lancamento'];
$filtro_pendentes = $_GET['filtro_pendentes'];

// Formata as datas para o formato dd/mm/yyyy
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

// Define o tipo de filtro
$filtro_tipoF = "";
$classe_entradas = '';
if ($filtro_tipo == "receber") {
    $filtro_tipoF = 'ENTRADAS / GANHOS';
    $classe_entradas = 'green'; 
} else {
    $filtro_tipoF = 'SAÍDAS / DESPESAS';
    $classe_entradas = 'red'; 
}

// Define o tipo de data
$filtro_dataF = "";
if ($filtro_data == "data_lanc") {
    $filtro_dataF = 'DATA DE LANÇAMENTO'; 
} elseif ($filtro_data == "data_venc") {
    $filtro_dataF = 'DATA DE VENCIMENTO';
} else {
    $filtro_dataF = "DATA DE PAGAMENTO";
}

// Define o tipo de lançamento
$filtro_lancamentoF = "";
if ($filtro_lancamento != "") {
    switch ($filtro_lancamento) {
        case 'Conta':
            $filtro_lancamentoF = $filtro_tipo == 'receber' ? 'Recebimentos' : 'Contas / Despesas';
            break;
        case 'Venda':
            $filtro_tipoF = 'VENDAS';
            $classe_entradas = '';
            break;
        case 'Cancelamento':
            $filtro_tipoF = 'VENDAS CANCELADAS';
            $classe_entradas = '';
            break;
        case 'Compra':
            $filtro_tipoF = 'COMPRAS';
            $classe_entradas = '';
            break;
        case 'Comissão':
            $filtro_tipoF = 'COMISSÕES';
            $classe_entradas = '';
            break;
        case 'Serviço':
            $filtro_tipoF = 'SERVIÇOS';
            $classe_entradas = '';
            break;
    }
}

// Define o estado dos pagamentos (pendentes ou pagos)
$filtro_pendentesF = "";
if ($filtro_pendentes == "Não") {
    $filtro_pendentesF = 'PENDENTES'; 
} elseif ($filtro_pendentes == "Sim") {
    $filtro_pendentesF = 'PAGAS';
}

// Define o intervalo de datas
$datas = $dataInicial == $dataFinal ? $dataInicialF : $dataInicialF . ' à ' . $dataFinalF;

// Texto do filtro
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

<?php if ($marca_dagua == 'Sim') { ?>
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
                    <b><big>RELATÓRIO DE <span style="color:<?php echo $classe_entradas ?>"><?php echo $filtro_tipoF ?> <?php if ($filtro_lancamentoF != "") { echo '('. mb_strtoupper($filtro_lancamentoF) .')'; } ?></span></big></b><br> 
                    <?php echo mb_strtoupper($texto_filtro) ?> <br> 
                    <?php echo mb_strtoupper($data_hoje) ?>
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
                <td style="width:12%">DATA LANÇAMENTO</td>
                <td style="width:12%">DATA VENCIMENTO</td>
                <td style="width:12%">DATA PAGAMENTO</td>
                <td style="width:17%">RECEBIDO POR</td>    
                <td style="width:15%">REFERÊNCIA</td>        
            </tr>
        </thead>
    </table>
</div>

<div id="footer" class="row">
    <hr style="margin-bottom: 0;">
    <table style="width:100%;">
        <tr style="width:100%;">
            <td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
            <td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página </p></td>
        </tr>
    </table>
</div>

<div id="content" style="margin-top: 0;">
    <table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
        <thead>
            <tbody>
                <?php
                // Inicializa variáveis para totais
                $total_valor = 0;
                $total_pendentes = 0;
                $total_pagas = 0;
                $pendentes = 0;
                $pagas = 0;

                // Consulta para obter os registros conforme filtros aplicados
                $query = $pdo->query("SELECT * from $filtro_tipo where $filtro_data >= '$dataInicial' and $filtro_data <= '$dataFinal' and pago LIKE '%$filtro_pendentes%' and referencia LIKE '%$filtro_lancamento%' order by $filtro_data asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                if (count($res) > 0) {
                    foreach ($res as $row) {
                        // Atribuição de valores
                        $id = $row['id'];
                        $descricao = $row['descricao'];
                        $valor = $row['valor'];
                        $data_lanc = $row['data_lanc'];
                        $data_venc = $row['vencimento'];
                        $data_pgto = $row['data_pgto'];
                        $usuario_pgto = $row['usuario_pgto'];
                        $pago = $row['pago'];
                        $referencia = $row['referencia'];

                        // Formatação de datas e valores
                        $data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
                        $data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
                        $data_pgtoF = $data_pgto != '0000-00-00' ? implode('/', array_reverse(explode('-', $data_pgto))) : 'Pendente';
                        $valorF = number_format($valor, 2, ',', '.');

                        // Consulta para obter o nome do usuário que realizou o pagamento
                        $query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_pgto'");
                        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                        $nome_usu_pgto = count($res2) > 0 ? $res2[0]['nome'] : '';

                        // Determinação da classe para indicar se está pago ou pendente
                        $classe_pago = $pago == 'Sim' ? 'verde.jpg' : 'vermelho.jpg';
                        if ($pago == 'Sim') {
                            $total_pagas += $valor;
                            $pagas += 1;
                        } else {
                            $total_pendentes += $valor;
                            $pendentes += 1;
                        }

                        // Formatação dos totais
                        $total_pagasF = number_format($total_pagas, 2, ',', '.');
                        $total_pendentesF = number_format($total_pendentes, 2, ',', '.');
                        ?>

                        <tr>
                            <td style="width:22%">
                                <img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/<?php echo $classe_pago ?>" width="8px">
                                <?php echo $descricao ?>
                            </td>
                            <td style="width:10%">R$ <?php echo $valorF ?></td>
                            <td style="width:12%"><?php echo $data_lancF ?></td>
                            <td style="width:12%"><?php echo $data_vencF ?></td>
                            <td style="width:12%"><?php echo $data_pgtoF ?></td>
                            <td style="width:17%"><?php echo $nome_usu_pgto ?></td>
                            <td style="width:15%"><?php echo $referencia ?></td>
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
