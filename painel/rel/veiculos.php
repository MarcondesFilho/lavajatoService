<?php 
// Inclui o arquivo de conexão com o banco de dados
require_once("../../conexao.php");
// Inclui o arquivo de formatação de data
require_once("data_formatada.php");

// Recebe o parâmetro 'filtrar_cliente' via GET
$filtrar_cliente = $_GET['filtrar_cliente'];

// Consulta a tabela 'clientes' para obter o nome do cliente
$query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$filtrar_cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if (count($res2) > 0) {
    $nome_cliente = $res2[0]['nome'];
} else {
    $nome_cliente = 'Sem Usuário';
}

// Define o filtro para a consulta de veículos baseado no cliente selecionado
if ($filtrar_cliente == "") {
    $filtro_cliente = '';
    $texto_filtro = '';
} else {
    $filtro_cliente = "WHERE cliente = '$filtrar_cliente'";
    $texto_filtro = 'FILTRO: CLIENTE: ' . $nome_cliente;
}

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
<?php 
if ($marca_dagua == 'Sim') { ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">    
<?php } ?>

<div id="header">
    <div style="border-style: solid; font-size: 10px; height: 50px;">
        <table style="width: 100%; border: 0px solid #ccc;">
            <tr>
                <td style="border: 1px solid #000; width: 7%; text-align: left;">
                    <img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="180px">
                </td>
                <td style="width: 30%; text-align: left; font-size: 13px;"></td>
                <td style="width: 1%; text-align: center; font-size: 13px;"></td>
                <td style="width: 47%; text-align: right; font-size: 9px; padding-right: 10px;">
                    <b><big>RELATÓRIO DE VEÍCULOS</big></b>
                    <br><?php echo mb_strtoupper($texto_filtro) ?> 
                    <br><?php echo mb_strtoupper($data_hoje) ?>
                </td>
            </tr>        
        </table>
    </div>
    <br>
    <table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom: 10px; width: 100%; table-layout: fixed;">
        <thead>
            <tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
                <td style="width:20%">MODELO</td>
                <td style="width:20%">MARCA</td>
                <td style="width:13%">PLACA</td>
                <td style="width:13%">COR</td>
                <td style="width:20%">CLIENTE</td>
                <td style="width:14%">CADASTRO</td>    
            </tr>
        </thead>
    </table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
    <table style="width: 100%;">
        <tr style="width: 100%;">
            <td style="width: 60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
            <td style="width: 40%; font-size: 10px; text-align: right;"><p class="page">Página</p></td>
        </tr>
    </table>
</div>

<div id="content" style="margin-top: 0;">
    <table style="width: 100%; table-layout: fixed; font-size: 9px; text-transform: uppercase;">
        <thead>
            <tbody>
                <?php
                // Consulta a tabela 'veiculos' para obter os veículos filtrados
                $query = $pdo->query("SELECT * FROM veiculos $filtro_cliente ORDER BY id DESC");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                $linhas = count($res);
                if ($linhas > 0) {
                    for ($i = 0; $i < $linhas; $i++) {
                        $id = $res[$i]['id'];
                        $placa = $res[$i]['placa'];
                        $data_cad = $res[$i]['data'];
                        $marca = $res[$i]['marca'];
                        $modelo = $res[$i]['modelo'];
                        $cor = $res[$i]['cor'];
                        $cor_hex = $res[$i]['cor_hex'];
                        $obs = $res[$i]['obs'];
                        $cliente = $res[$i]['cliente'];
                        $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));

                        // Consulta a tabela 'clientes' para obter o nome do cliente
                        $query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente'");
                        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                        if (count($res2) > 0) {
                            $nome_cliente = $res2[0]['nome'];
                        } else {
                            $nome_cliente = 'Sem Usuário';
                        }
                ?>

                <tr>
                    <td style="width: 20%"><?php echo $modelo ?></td>
                    <td style="width: 20%"><?php echo $marca ?></td>
                    <td style="width: 13%"><?php echo $placa ?></td>
                    <td style="width: 13%"><?php echo $cor ?></td>
                    <td style="width: 20%"><?php echo $nome_cliente ?></td>
                    <td style="width: 14%"><?php echo $data_cadF ?></td>
                </tr>

                <?php } } ?>
            </tbody>
        </thead>
    </table>
</div>
<hr>
</body>
</html>
