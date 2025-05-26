<?php

// $servername = "auth-db1660.hstgr.io";
// $username = "u182528050_ladoceria";
// $password = '@sSNwx4s$W&AW?A6';
// $dbname = "u182528050_ladoceria";

//quando for usar o servidor local, descomente as linhas abaixo e comente as linhas acima

$servername = "localhost";
$username = "root";
$password = '';
$dbname = "ladoce";

$conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Conexão falhou: " . $conn->connect_error);
// }
// else {
//      echo "Conexão bem-sucedida!";
// }
?>