<?php
include '../Db/conexao.php';
session_start();
$id = $_POST['id'];

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  session_destroy();
  echo "Usuário excluído com sucesso!";
} else {
  echo "Erro ao excluir usuário.";
}
