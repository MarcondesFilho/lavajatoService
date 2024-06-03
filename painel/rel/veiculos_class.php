<?php 
// Inclui o arquivo de conexão com o banco de dados
require_once("../../conexao.php");

// Recebe o parâmetro 'filtrar_cliente' via POST. O operador @ suprime mensagens de erro caso a variável não esteja definida
$filtrar_cliente = @$_POST['filtrar_cliente'];

$url_sistema = 'http://localhost:81/lavajato/';

// Gera o conteúdo HTML para o relatório com base no filtro de cliente
$html = file_get_contents($url_sistema . "painel/rel/veiculos.php?filtrar_cliente=$filtrar_cliente");

// Carrega a biblioteca Dompdf
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Define os cabeçalhos HTTP para a transferência do arquivo
header("Content-Transfer-Encoding: binary");
header("Content-Type: application/pdf");

// Inicializa a classe Dompdf com as opções
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Define o tamanho do papel e a orientação da página
$pdf->setPaper('A4', 'portrait');

// Carrega o conteúdo HTML no Dompdf
$pdf->loadHtml($html);

// Renderiza o PDF
$pdf->render();

// Nomeia o PDF gerado e envia o arquivo para o navegador
$pdf->stream(
	'veiculos.pdf', // Nome do arquivo
	array("Attachment" => false) // Configuração para abrir o PDF no navegador (false) ou fazer download (true)
);

?>
