<?php 
// Inclui o arquivo de conexão com o banco de dados
require_once("../../conexao.php");

// Recebe os parâmetros via POST
$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$pago = $_POST['pago'];
$tipo_data = $_POST['tipo_data'];

// Certifique-se de que a URL está correta
$url_sistema = 'http://localhost:81/lavajato/';

// Constrói a URL
$url = $url_sistema . "painel/rel/receber.php?dataInicial=$dataInicial&dataFinal=$dataFinal&pago=$pago&tipo_data=$tipo_data";

// Carrega o conteúdo HTML do arquivo remoto com os parâmetros passados na URL
$html = file_get_contents($url);

// Verifica se houve erro ao carregar o conteúdo HTML
if ($html === false) {
    die("Erro ao carregar conteúdo HTML. Verifique a URL: $url");
}


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
    'contas_receber.pdf',
    array("Attachment" => false)
);
?>
