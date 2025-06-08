<?php
include '../Db/conexao.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- <meta http-equiv="refresh" content="5" /> -->
  <title>LA DOCERIA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Miniver&family=MonteCarlo&family=Playwrite+RO:wght@100..400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Miniver&family=Playwrite+RO:wght@100..400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <header>
    <nav class="nav-bar">
      <a href = "#" class="nav-logo">
        <h2 class="logo-text">La doceria</h2>
      </a>
      <ul class="nav-menu">
        
       <li class="nav-item">
      <a href="#sobre" class="nav-link">Sobre</a>
      </li>
      <li class="nav-item">
      <a href="#cardapio" class="nav-link">Cardápio</a>
      </li>
      <li class="nav-item">
      <a href="#pedido" class="nav-link">Pedido</a>
      </li>
      <li class="nav-item">
      <a href="#contato" class="nav-link">Contato</a>
      </li>
      <?php if (isset($_SESSION['usuario_nome'])): ?>
    <li class="nav-item">
      <span class="nav-link" id="abrirPerfil" style="font-weight:bold; cursor:pointer;"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
    </li>
    <li class="nav-item">
      <a href="logout.php" class="nav-link">Sair</a>
    </li>
<?php else: ?>
    <li class="nav-item">
      <a href="registro.php" class="nav-link">Registro</a>
    </li>
    <li class="nav-item">
      <a href="login.php" class="nav-link">Login</a>
    </li>
<?php endif; ?>
    </ul>
    </nav>
  </header>
<main>
    <section class="hero-section">
      <div class="section-content">
        <div class="hero-details">
        <h2 class="title">Bem-vindo à La Doceria!</h2>
        <h3 class="subtitle">Doces feitos com amor e carinho.</h3>
        <p class="description"> Bem-vindo a La Doceria, onde cada sabor te conta uma história.</p>

        <div class="buttons"> 
          <a href="#cardapio" class="button order-now">Ver Cardápio</a>
          <a href="#pedido" class="button complete-order">Fazer Pedido</a>

        </div>
      </div>
      <div class="hero-image-wrapper">
        <img src="../imgs/bolo_morango.png" alt="Bolo de morango capa" class="hero-image" />
      </div>
    </section>
</main>
 

  <section id="carousel">
    <div class="carrossel">
      <div class="slide ativo">
        <img src="../imgs/bolo_pote.png" alt="Bolo de Pote">
        <div class="descricao direita">
          <h2>Bolo de Pote</h2>
          <p>Sabores variados e recheios cremosos.</p>
        </div>
      </div>
      <div class="slide">
        <img src="../imgs/brigadeiro.png" alt="Brigadeiro">
        <div class="descricao esquerda">
          <h2>Brigadeiro Gourmet</h2>
          <p>O clássico repaginado com sabores intensos.</p>
        </div>
      </div>
      <div class="slide">
        <img src="../imgs/coxinha_d.png" alt="Coxinha Doce">
        <div class="descricao direita">
          <h2>Coxinha Doce</h2>
          <p>A queridinha da casa com recheio de brigadeiro.</p>
        </div>
      </div>
      <button class="anterior">&#10094;</button>
      <button class="proximo">&#10095;</button>
    </div>
  </section>

  <section id="sobre">
    <h2>Sobre Nós</h2>
    <p>A LA DOCERIA nasceu do amor por confeitar e espalhar sorrisos. Usamos ingredientes de qualidade para criar momentos doces e inesquecíveis para nossos clientes.</p>
  </section>

  <section id="cardapio">
    <h2>Nosso Cardápio</h2>
    <div class="produtos">
      <div class="produto">
        <img src="../imgs/bolo_pote.png" alt="Bolo de Pote">
        <h3>Bolo de Pote</h3>
        <p>Sabores variados e recheios cremosos.</p>
      </div>
      <div class="produto">
        <img src="../imgs/brigadeiro.png" alt="Brigadeiro">
        <h3>Brigadeiro</h3>
        <p>Tradicional, gourmet e especiais.</p>
      </div>
      <div class="produto">
        <img src="..//imgs/coxinha_d.png" alt="Coxinha Doce">
        <h3>Coxinha Doce</h3>
        <p>A queridinha da casa com recheio de brigadeiro.</p>
      </div>
    </div>
  </section>

  <section id="pedido">
    <h2>Fazer Pedido</h2>
    <form id="pedidoForm" action="../Db/salvar_pedido.php" method="POST">
      <input type="text" name="nome" id="nome" placeholder="Seu nome" required>
      <select name="tipo" id="produto">
        <option value="Bolo de Pote">Bolo de Pote</option>
        <option value="Brigadeiro">Brigadeiro</option>
        <option value="Coxinha Doce">Coxinha Doce</option>
      </select>
      <input type="number" name="qtd" id="quantidade" placeholder="Quantidade" required>
      <textarea name="obs" id="obs" placeholder="Observações"></textarea>
      <textarea name="end" id="end" placeholder="Endereço de entrega" required></textarea>
      <button type="submit">Confirmar Pedido</button>
    </form>
  </section>

  <section id="contato">
    <h2>Contato</h2>
    <p>WhatsApp: <a href="https://wa.me/5573988592733" target="_blank">(75) 99177-9729</a></p>
    <p>Instagram: @lla.doceriaa</p>
    <p>Endereço: Rua principal, Capoeiruçu, Cachoeira, Bahia, Brazil 44300000</p>
  </section>

  <footer>
    <p>&copy; 2025 LA DOCERIA. Todos os direitos reservados.</p>
  </footer>

  <script>
    const form = document.getElementById('pedidoForm');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const nome = document.getElementById('nome').value;
      const produto = document.getElementById('produto').value;
      const qtd = document.getElementById('quantidade').value;
      const obs = document.getElementById('obs').value;
      const end = document.getElementById('end').value;
      const msg = `Olá! Gostaria de fazer um pedido:\n- ${produto} x${qtd}\nObservações: ${obs}\nNome: ${nome}\nEndereço: ${end}`;
      const zap = `https://wa.me/5573988592733?text=${encodeURIComponent(msg)}`;
      window.open(zap, '_blank');
    });

    const slides = document.querySelectorAll('.slide');
    const btnAnt = document.querySelector('.anterior');
    const btnProx = document.querySelector('.proximo');
    let indice = 0;

    function mostrarSlide(n) {
      slides.forEach((slide, i) => {
        slide.classList.remove('ativo');
        if (i === n) slide.classList.add('ativo');
      });
    }

    btnAnt.addEventListener('click', () => {
      indice = (indice - 1 + slides.length) % slides.length;
      mostrarSlide(indice);
    });

    btnProx.addEventListener('click', () => {
      indice = (indice + 1) % slides.length;
      mostrarSlide(indice);
    });

    
  </script>

  <!-- Botão para abrir/fechar o chatbot -->
<button id="toggleChatbot" style="
  position: fixed;
  bottom: 40px;
  right: 20px;
  z-index: 10000;
  background: #d26070;
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  font-size: 1.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  cursor: pointer;
">💬</button>

<!-- Iframe do chatbot Flask -->
<iframe 
  id="chatbotFrame"
  src="../chatbot/templates/cliente.php"
  style="position:fixed; bottom:94px; right:20px; width:350px; height:500px; border:2px solid #d26070; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.15); z-index:9999; background:#fff; display:none;"
  title="Chatbot"
  allow="clipboard-write"
></iframe>

<script>
  const chatbotFrame = document.getElementById('chatbotFrame');
  const toggleButton = document.getElementById('toggleChatbot');
  toggleButton.addEventListener('click', () => {
    chatbotFrame.style.display = chatbotFrame.style.display === 'none' ? 'block' : 'none';
  });
</script>

<!-- Modal do Perfil -->
<div id="modalPerfil" style="display:none;">
  <div class="modal-conteudo">
    <button onclick="fecharPerfil()" class="modal-fechar">&times;</button>
  </div>
</div>

<script>
  document.getElementById('abrirPerfil')?.addEventListener('click', function(e) {
    e.preventDefault();
    const modal = document.getElementById('modalPerfil');
    const conteudo = modal.querySelector('.modal-conteudo');
    const oldIframe = conteudo.querySelector('iframe');
    if (oldIframe) oldIframe.remove();
    const iframe = document.createElement('iframe');
    iframe.src = '../perfil/perfil.php';
    conteudo.appendChild(iframe);
    modal.style.display = 'flex';
  });

  function fecharPerfil() {
  document.getElementById('modalPerfil').style.display = 'none';
  const iframe = document.querySelector('#modalPerfil iframe');
  if (iframe) iframe.remove();
  window.location.reload();
}

  window.addEventListener('message', function(event) {
    if (event.data === 'usuarioExcluido') {
      const modal = document.getElementById('modalPerfil');
      const conteudo = modal.querySelector('.modal-conteudo');
      const iframe = conteudo.querySelector('iframe');
      if (iframe) iframe.remove();
      conteudo.innerHTML = '<button onclick="fecharPerfil()" class="modal-fechar">&times;</button>';
      conteudo.style.background = '#fff';
      modal.style.display = 'flex';
      setTimeout(() => {
        window.location.reload();
      }, 200);
    }
  });
</script>
</body>

</html>