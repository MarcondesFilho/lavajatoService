<?php
require_once("../../conexao.php");

$dadosXls = "<table border='1'>";

$dadosXls .= "<tr>";
$dadosXls .= "<th>Nome</th>";
$dadosXls .= "<th>Telefone</th>";
$dadosXls .= "<th>Email</th>";
$dadosXls .= "<th>CPF / CNPJ</th>";
$dadosXls .= "<th>Tipo Pessoa</th>";
$dadosXls .= "<th>Data Cadastro</th>";
$dadosXls .= "<th>Data Nascimento</th>";
$dadosXls .= "</tr>";

$query = $pdo->query("SELECT * from clientes order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
    foreach ($res as $row) {
        $id = $row['id'];
        $nome = $row['nome'];
        $telefone = $row['telefone'];
        $email = $row['email'];    
        $endereco = $row['endereco'];
        $tipo_pessoa = $row['tipo_pessoa'];
        $cpf = $row['cpf'];
        
        $data_cad = $row['data_cad'];
        $data_nasc = $row['data_nasc'];

        $data_cadF = !empty($data_cad) ? implode('/', array_reverse(explode('-', $data_cad))) : '';
        $data_nascF = !empty($data_nasc) ? implode('/', array_reverse(explode('-', $data_nasc))) : '';


        $tel_whatsF = '55' . preg_replace('/[ ()-]+/', '', $telefone);

        $dadosXls .= "<tr>";
        $dadosXls .= "<td>" . mb_convert_encoding($nome, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($telefone, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($email, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($cpf, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($tipo_pessoa, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($data_cadF, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . mb_convert_encoding($data_nascF, 'ISO-8859-1', 'UTF-8') . "</td>";
        $dadosXls .= "</tr>";
    }
}

$dadosXls .= "</table>";

$arquivo = "rel-clientes.xls";

header('Content-Type: application/vnd.ms-excel; charset=ISO-8859-1');
header('Content-Disposition: attachment;filename="' . $arquivo . '"');
header('Cache-Control: max-age=0');

echo $dadosXls;
exit;
?>