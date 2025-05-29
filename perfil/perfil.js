document.getElementById('perfilForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  const response = await fetch('atualizar_usuario.php', {
    method: 'POST',
    body: formData
  });
  const result = await response.text();
  document.getElementById('mensagem').innerText = result;
});

document.getElementById('deletarConta').addEventListener('click', async function() {
  if (!confirm("Tem certeza que deseja excluir sua conta?")) return;

  const id = document.querySelector('input[name="id"]').value;
  const response = await fetch('deletar_usuario.php', {
    method: 'POST',
    body: new URLSearchParams({ id })
  });
  const result = await response.text();
  alert(result);
  if (result.includes("excluído")) {
    window.location.href = "../logout.php";
  }
});
