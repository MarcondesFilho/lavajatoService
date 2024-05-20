<?php 
// Inclui o arquivo de conexão com o banco de dados
require_once("../../conexao.php");

// Recebe os parâmetros via POST
$filtro_data = $_POST['filtro_data'];
$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$filtro_tipo = "pagar"; // Define o tipo de filtro como "pagar"
$filtro_lancamento = urlencode($_POST['filtro_lancamento']);
$filtro_pendentes = $_POST['filtro_pendentes'];

// Gera o conteúdo HTML do arquivo remoto com os parâmetros passados na URL
$html = file_get_contents($url_sistema . "painel/rel/sintetico.php?filtro_data=$filtro_data&dataInicial=$dataInicial&dataFinal=$dataFinal&filtro_tipo=$filtro_tipo&filtro_lancamento=$filtro_lancamento&filtro_pendentes=$filtro_pendentes");

// Inclui a biblioteca Dompdf
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Define cabeçalhos HTTP para o tipo de conteúdo
header("Content-Transfer-Encoding: binary");
header("Content-Type: application/pdf");

// Inicializa a classe Dompdf com as opções
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Define o tamanho do papel e a orientação da página
$pdf->setPaper('A4', 'portrait');

// Carrega o conteúdo HTML
$pdf->loadHtml($html);

// Renderiza o PDF
$pdf->render();

// Nomeia e envia o PDF gerado para o navegador
$pdf->stream(
    'sintetico.pdf',
    array("Attachment" => false)
);
?>
