<?php 
// Conectar ao banco de dados
include('../../conexao.php');

// Receber o ID do POST
$id = $_POST['id'];

// Consultar a tabela 'pagar' para obter os detalhes do pagamento
$query = $pdo->query("SELECT * from pagar where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Verificar se a consulta retornou algum resultado
if (count($res) > 0) {
    // Atribuir os valores obtidos da consulta a variáveis
    $data = $res[0]['data_pgto'];
    $funcionario = $res[0]['funcionario'];
    $fornecedor = $res[0]['fornecedor'];
    $subtotal = $res[0]['subtotal'];
    $descricao = $res[0]['descricao'];
    $valor = $res[0]['valor'];
    $multa = $res[0]['multa'];
    $juros = $res[0]['juros'];
    $desconto = $res[0]['desconto'];
    $taxa = $res[0]['taxa'];
    $obs = $res[0]['obs'];

    // Formatar a data para o formato dd/mm/yyyy
    $dataF = implode('/', array_reverse(explode('-', $data)));

    // Formatar valores numéricos para o formato brasileiro (vírgula como separador decimal)
    $subtotalF = number_format($subtotal, 2, ',', '.');
    $valorF = number_format($valor, 2, ',', '.');
    $multaF = number_format($multa, 2, ',', '.');
    $jurosF = number_format($juros, 2, ',', '.');
    $descontoF = number_format($desconto, 2, ',', '.');
    $taxaF = number_format($taxa, 2, ',', '.');

    // Inicializar variáveis para armazenar informações da pessoa (fornecedor ou funcionário)
    $nome_pessoa = '';
    $telefone_pessoa = '';
    $pix_pessoa = '';
    $tipo_pessoa = 'Pessoa';

    // Verificar se há fornecedor ou funcionário associado ao pagamento
    if ($fornecedor != 0 || $funcionario != 0) {
        if ($fornecedor != 0) {
            $tab = 'fornecedores';
            $id_pessoa = $fornecedor;
            $tipo_pessoa = 'Fornecedor';
        } elseif ($funcionario != 0) {
            $tab = 'usuarios';
            $id_pessoa = $funcionario;
            $tipo_pessoa = 'Funcionário';
        }

        // Consultar a tabela correspondente para obter informações da pessoa
        $query2 = $pdo->query("SELECT * FROM $tab where id = '$id_pessoa'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);

        if (count($res2) > 0) {
            $nome_pessoa = $res2[0]['nome'];
            $telefone_pessoa = $res2[0]['telefone'];
            $pix_pessoa = $res2[0]['pix'];
        }
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
        <th class="ttu" class="title" colspan="3"><?php echo $nome_sistema ?></th>
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
        <th class="ttu margem-superior" colspan="3">
            RECIBO DE PAGAMENTO Nº <?php echo $id ?> 
        </th>
    </tr>
    <tbody>
        <tr>
            <td colspan="2" width="70%">
                Eu, <?php echo $nome_pessoa ?>, atesto que recebi da empresa <?php echo $nome_sistema ?> a quantia de <b>R$ <?php echo $subtotalF ?></b> na data do dia <?php echo $dataF ?>.
            </td>
        </tr>
    </tbody>
    <tfoot>
        <?php if($obs != "") { ?>
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
