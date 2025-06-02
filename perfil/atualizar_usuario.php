<?php
include '../Db/conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

$sql = "UPDATE usuarios SET nome=?, email=?" . ($senha ? ", senha=?" : "") . " WHERE id=?";
$stmt = $conn->prepare($sql);

if ($senha) {
  $stmt->bind_param("sssi", $nome, $email, $senha, $id);
} else {
  $stmt->bind_param("ssi", $nome, $email, $id);
}

if ($stmt->execute()) {
  echo "Dados atualizados com sucesso!";
} else {
  echo "Erro ao atualizar: " . $conn->error;
}
