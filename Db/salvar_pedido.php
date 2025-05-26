<?php
include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $qtd = $_POST['qtd'] ?? 0;
    $obs = $_POST['obs'] ?? '';
    $horario = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO pedidos (nome, tipo, qtd, obs, horario) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nome, $tipo, $qtd, $obs, $horario);

    if ($stmt->execute()) {
        echo "<script>alert('Pedido salvo com sucesso!');window.location.href='../pagina/index.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar pedido.');window.history.back();</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    header('Location: ../pagina/index.php');
    exit;
}
?>