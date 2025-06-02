<?php
session_start();
include '../Db/conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $permitidos = ['jpg', 'jpeg', 'png', 'jfif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidos)) {
        die('Tipo de imagem não permitido!');
    }
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        die('Imagem muito grande! Máximo 2MB.');
    }
    $novo_nome = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $novo_nome);
    $foto = $novo_nome;
}

$sql = "UPDATE usuarios SET nome=?, email=?";
$params = [$nome, $email];

if (!empty($senha)) {
    $sql .= ", senha=?";
    $params[] = password_hash($senha, PASSWORD_DEFAULT);
}
if ($foto) {
    $sql .= ", foto=?";
    $params[] = $foto;
}
$sql .= " WHERE id=?";
$params[] = $id;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

header('Location: perfil.php');
exit;
?>