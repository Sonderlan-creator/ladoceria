<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  echo "<script>window.parent.postMessage('usuarioExcluido', '*');</script>";
  exit;
}
include '../Db/conexao.php';

$id = $_SESSION['usuario_id']; 
$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Meu Perfil - LA DOCERIA</title>
  <link rel="stylesheet" href="perfil.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow-lg p-4">
      <div class="d-flex align-items-center mb-4">
        <?php
        if ($user['foto']) {
          echo '<img id="avatarImg" src="../uploads/' . $user['foto'] . '" class="rounded-circle me-3" width="80" height="80" alt="avatar" style="cursor:pointer;">';
        } else {
          echo '<img id="avatarImg" src="../imgs/frenteladoceria.jpg" class="rounded-circle me-3" width="80" height="80" alt="avatar" style="cursor:pointer;">';
        }
        ?>
        <div>
          <h2 class="mb-0">Olá, <?= $user['nome'] ?>!</h2>
          <p class="text-muted">Seja bem-vindo(a) ao seu espaço de cliente 🍰</p>
        </div>
        <div class="ms-auto">
          <button class="btn btn-outline-danger" id="deletarConta">Excluir Conta</button>
        </div>
      </div>

      <div class="row">
        <!-- Card 1 -->
        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Pedidos feitos</h5>
              <p class="card-text display-6">12</p>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Doces Favoritos</h5>
              <p class="card-text display-6">5</p>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Último pedido</h5>
              <p class="card-text">25/05/2025</p>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-4">

      <div class="row">
        <!-- Form de edição -->
        <div class="col-md-6">
          <h4>Editar informações</h4>
          <form id="perfilForm" enctype="multipart/form-data" method="POST" action="atualizar_perfil.php">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="mb-3">
              <label>Nome</label>
              <input type="text" class="form-control" name="nome" value="<?= $user['nome'] ?>" required>
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
            </div>
            <div class="mb-3">
              <label>Nova Senha</label>
              <input type="password" class="form-control" name="senha" placeholder="Deixe em branco para manter">
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
          </form>
          <div id="mensagem" class="mt-2 text-success"></div>
        </div>

        <!-- Tabela de ações// ATENÇÃO EDCARLOS MOTTA, PENSEMOS EM COMO FAZER PRA ISSO AQ SER ATUALIZADO AUTOMATICAMENTE COM O PEDIDO FEITO -->
        <div class="col-md-6">
          <h4>Histórico de ações</h4>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Ação</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Pedido confirmado</td>
                <td>20/05/2025</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Perfil atualizado</td>
                <td>15/05/2025</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Cadastro criado</td>
                <td>01/05/2025</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script src="perfil.js"></script>
  <script>
    document.getElementById('avatarImg').onclick = function() {
      document.getElementById('fotoInput').click();
    };
    document.getElementById('fotoInput').onchange = function() {
      document.getElementById('perfilForm').submit();
    };

    document.getElementById('deletarConta').onclick = function() {
      if (confirm('Tem certeza que deseja excluir sua conta?')) {
        fetch('deletar_usuario.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=<?= $user['id'] ?>'
        })
        .then(response => response.text())
        .then(data => {
          if (data.includes('sucesso')) {
            // Fecha o modal imediatamente
            window.parent.postMessage('usuarioExcluido', '*');
          } else {
            alert('Erro ao excluir usuário.');
          }
        });
      }
    };
  </script>
</body>
</html>
