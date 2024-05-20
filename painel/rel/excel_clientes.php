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

        $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
        $data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));

        $tel_whatsF = '55' . preg_replace('/[ ()-]+/', '', $telefone);

        $dadosXls .= "<tr>";
        $dadosXls .= "<td>" . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($tipo_pessoa, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($data_cadF, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "<td>" . htmlspecialchars($data_nascF, ENT_QUOTES, 'UTF-8') . "</td>";
        $dadosXls .= "</tr>";
    }
}

$dadosXls .= "</table>";

$arquivo = "rel-clientes.xls";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $arquivo . '"');
header('Cache-Control: max-age=0');

echo $dadosXls;
exit;
?>