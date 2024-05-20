<?php 
// Inclui o arquivo de conexão com o banco de dados
include('../../conexao.php');

// Recebe o ID do POST
$id = $_POST['id'];

// Consulta a tabela 'receber' para obter os detalhes do pagamento
$query = $pdo->query("SELECT * from receber where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Verifica se a consulta retornou resultados
if (count($res) > 0) {
    // Atribui os valores obtidos da consulta a variáveis
    $data = $res[0]['data_pgto'];
    $cliente = $res[0]['cliente'];
    $subtotal = $res[0]['subtotal'];
    $descricao = $res[0]['descricao'];
    $valor = $res[0]['valor'];
    $multa = $res[0]['multa'];
    $juros = $res[0]['juros'];
    $desconto = $res[0]['desconto'];
    $taxa = $res[0]['taxa'];
    $obs = $res[0]['obs'];

    // Formata a data para o formato dd/mm/yyyy
    $dataF = implode('/', array_reverse(explode('-', $data)));

    // Formata valores numéricos para o formato brasileiro (vírgula como separador decimal)
    $subtotalF = number_format($subtotal, 2, ',', '.');
    $valorF = number_format($valor, 2, ',', '.');
    $multaF = number_format($multa, 2, ',', '.');
    $jurosF = number_format($juros, 2, ',', '.');
    $descontoF = number_format($desconto, 2, ',', '.');
    $taxaF = number_format($taxa, 2, ',', '.');

    // Consulta a tabela 'clientes' para obter informações do cliente
    $query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($res2) > 0) {
        // Atribui os valores obtidos da consulta a variáveis
        $nome_cliente = $res2[0]['nome'];    
        $tel_cliente = $res2[0]['telefone'];        
    }
} else {
    echo "Nenhum registro encontrado.";
    exit;
}
?>

<!-- Incluir jQuery -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php if(isset($impressao_automatica) && $impressao_automatica == 'Sim'){ ?>
<script type="text/javascript">
    $(document).ready(function() {            
        window.print();
        window.close(); 
    });
</script>
<?php } ?>

<!-- Estilos CSS para o recibo -->
<style type="text/css">
    * {
        margin: 0px;
        padding: 0px;
        background-color: #ffffff;
        color: #000;
        font-family: TimesNewRoman, Geneva, sans-serif;
    }

    .text-center {
        text-align: center;
    }

    .ttu {
        text-transform: uppercase;
        font-weight: bold;
        font-size: 1.2em;
    }

    .printer-ticket {
        display: table !important;
        width: 100%;
        max-width: 400px;
        font-weight: light;
        line-height: 1.3em;
        padding: 0px;
        font-size: 12px;
        color: #000;
    }

    th {
        font-weight: inherit;
        padding: 5px;
        text-align: center;
        border-bottom: 1px dashed #000000;
    }

    .cor {
        color: #000000;
    }

    .margem-superior {
        padding-top: 5px;
    }
</style>

<!-- Estrutura HTML do recibo -->
<table class="printer-ticket">
    <tr>
        <th class="ttu title" colspan="3"><?php echo $nome_sistema ?></th>
    </tr>
    <tr style="font-size: 10px">
        <th colspan="3">
            <?php echo $endereco_sistema ?> <br />
            Contato: <?php echo $telefone_sistema ?>  
            <?php if($cnpj_sistema != "") { ?> 
                / CNPJ <?php echo $cnpj_sistema ?>
            <?php } ?>
        </th>
    </tr>
    <tr>
        <th colspan="3">Cliente <?php echo $nome_cliente ?> Tel: <?php echo $tel_cliente ?></th>
    </tr>
    <tr>
        <th class="ttu margem-superior" colspan="3">RECIBO DE PAGAMENTO Nº <?php echo $id ?></th>
    </tr>
    <tbody>
        <tr>
            <td colspan="2" width="70%"><?php echo $descricao ?></td>                
            <td align="right">R$ <?php echo $valorF; ?></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="ttu cor" colspan="3"></th>
        </tr>    

        <?php if($desconto > 0){ ?>
        <tr>
            <td colspan="2">Total Desconto</td>
            <td align="right">R$ <?php echo $descontoF ?></td>
        </tr>
        <?php } ?>

        <?php if($taxa > 0){ ?>
        <tr>
            <td colspan="2">Total Taxa</td>
            <td align="right">R$ <?php echo $taxaF ?></td>
        </tr>
        <?php } ?>

        <?php if($juros > 0){ ?>
        <tr>
            <td colspan="2">Total Juros</td>
            <td align="right">R$ <?php echo $jurosF ?></td>
        </tr>
        <?php } ?>

        <?php if($multa > 0){ ?>
        <tr>
            <td colspan="2">Total Multa</td>
            <td align="right">R$ <?php echo $multaF ?></td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="2">SubTotal</td>
            <td align="right"><b>R$ <?php echo $subtotalF ?></b></td>
        </tr>

        <?php if($obs != ""){ ?>
        <tr>
            <th colspan="3"></th>
        </tr>
        <tr style="margin-top:2px; text-align:center">
            <td colspan="3"><small><b>OBS:</b> <?php echo $obs ?></small></td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>

<br><br>
<div align="center">__________________________</div>
<div align="center"><small>Assinatura</small></div>
