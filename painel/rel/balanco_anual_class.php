<?php 
require_once("../../conexao.php");

$url_sistema = 'http://localhost:81/lavajato/';

// Obter o conteúdo HTML do arquivo remoto
$html = file_get_contents($url_sistema . "painel/rel/balanco_anual.php");

// Carregar Dompdf
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Define cabeçalhos HTTP para o tipo de conteúdo
header("Content-Transfer-Encoding: binary");
header("Content-Type: application/pdf");

// Inicializar a classe Dompdf com opções
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new Dompdf($options);

// Definir o tamanho do papel e a orientação da página
$pdf->setPaper('A4', 'portrait');

// Carregar o conteúdo HTML
$pdf->loadHtml($html);

// Renderizar o PDF
$pdf->render();

// Nomear e enviar o PDF gerado para o navegador
$pdf->stream(
    'balanco_anual.pdf',
    array("Attachment" => false)
);
?>