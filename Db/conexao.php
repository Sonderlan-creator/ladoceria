<?php
// Configuração para servidor remoto (descomente para usar em produção)
// $servername = "auth-db1660.hstgr.io";
// $username = "u182528050_ladoceria";
// $password = '@sSNwx4s$W&AW?A6';
// $dbname = "u182528050_ladoceria";

// Configuração para localhost (descomente para usar localmente)
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "u182528050_ladoceria";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Não exiba mensagem de sucesso em produção
?>