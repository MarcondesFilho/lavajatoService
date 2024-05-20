<?php 
// Inclui o arquivo de conexão com o banco de dados
include('../../conexao.php');

// Recebe o ID via POST
$id = $_POST['id'];

// Gera o conteúdo HTML do arquivo remoto com o ID passado na URL
$html = file_get_contents($url_sistema . "painel/rel/recibo_conta.php?id=$id");

// Inclui a biblioteca Dompdf
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Define cabeçalhos HTTP para o tipo de conteúdo
header("Content-Transfer-Encoding: binary");
header("Content-Type: application/pdf");

// Inicializa a classe Dompdf com as opções
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new Dompdf($options);

// Define o tamanho do papel e a orientação da página
$pdf->setPaper(array(0, 0, 595.28, 320.89));

// Carrega o conteúdo HTML
$pdf->loadHtml($html);

// Renderiza o PDF
$pdf->render();

// Nomeia e envia o PDF gerado para o navegador
$pdf->stream(
    'reciboConta.pdf',
    array("Attachment" => false)
);
?>
