<?php
include_once '../Db/conexao.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome);
        $stmt->fetch();
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nome'] = $nome;
        echo "<script>alert('Login realizado com sucesso!');window.location.href='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('E-mail ou senha incorretos!');window.history.back();</script>";
        exit;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Miniver&family=MonteCarlo&family=Playwrite+RO:wght@100..400&display=swap" rel="stylesheet">
  <title>Login | LA DOCERIA</title>
  
  <style>
    :root {
      --color-primary: #5c3d2e;
      --color-secondary: #402020;
      --color-tertiary: #FCE4D8;
      --color-quaternary: #fff0f5;
      --color-accent: #d26070;
      --border-radius-m: 30px;
      --font-family-title: 'Poppins', sans-serif;
      --shadow: 0 8px 32px 0 rgba(60, 30, 10, 0.15);
      --transition: 0.3s cubic-bezier(.4,0,.2,1);
    }
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: 
      linear-gradient(rgba(255, 255, 255, 0.8),rgba(0, 0, 0, 0.8)), url('../imgs/fundo_tela_login.png') no-repeat center center fixed;
      background-size: cover;
      color: var(--color-primary);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      ba
    }
    .logo-text{
      font-family: "MonteCarlo", cursive;
    }
    .nav-bar {
      width: 100%;
      display: flex;
      align-items: center;
      padding: 0;
      margin: 0;
      min-height: 80px;
      background: transparent;
      box-sizing: border-box;
    }
    .nav-logo {
      text-decoration: none;
      margin-left: 2rem;
      margin-top: 1.5rem;
      display: flex;
      align-items: center;
    }
    .logo-text {
      /* font-family: var(--font-family-title); */
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--color-primary);
      letter-spacing: 2px;
      transition: color var(--transition);
      text-shadow: 0 2px 8px #fce4d8a0;
      text-decoration: none;
    }
    .logo-text:hover {
      color: var(--color-accent);
      text-shadow: 0 4px 16px #d2607080;
    }
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100vw;
    }
    .center-container {
      width: 100vw;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 70vh;
    }
    .hero-section {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: unset;
      padding: 0;
    }
    .section-content {
      width: 100%;
      max-width: 420px;
      background: rgba(255, 255, 255, 0.8); /* Opacidade ajustada */
      border-radius: var(--border-radius-m);
      box-shadow: var(--shadow);
      padding: 2.5rem 2rem;
      margin: 0 auto;
      animation: fadeIn 0.8s;
      transition: box-shadow var(--transition), transform var(--transition);
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .section-content:hover {
      box-shadow: 0 12px 40px 0 rgba(60, 30, 10, 0.22);
      transform: translateY(-2px) scale(1.01);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(40px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .hero-details {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
    .hero-details .title {
      font-size: 2.3rem;
      color: var(--color-accent);
      margin-bottom: 2rem;
      text-align: center;
      font-family: var(--font-family-title);
      font-weight: 700;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #fce4d8a0;
      animation: fadeIn 1.2s;
    }
    .form-bg {
      background: linear-gradient(120deg, var(--color-tertiary) 80%, #fff0f5 100%);
      border-radius: var(--border-radius-m);
      padding: 1.5rem 1rem 1.2rem 1rem;
      width: 100%;
      margin-bottom: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      box-shadow: 0 2px 12px 0 rgba(210, 96, 112, 0.07);
      transition: box-shadow var(--transition);
    }
    .form-bg:hover {
      box-shadow: 0 4px 24px 0 rgba(210, 96, 112, 0.13);
    }
    .formulario {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }
    .formulario input {
      width: 90%;
      padding: 0.9rem 1.1rem;
      border: 1.5px solid #eee;
      border-radius: 12px;
      font-size: 1.08rem;
      background: #fafafa;
      color: var(--color-primary);
      outline: none;
      transition: border var(--transition), box-shadow var(--transition), background var(--transition);
      box-shadow: 0 1px 4px 0 #fce4d8a0;
    }
    .formulario input:focus {
      border: 1.5px solid var(--color-accent);
      box-shadow: 0 2px 8px 0 #d2607080;
      background: #fff;
    }
    .formulario button {
      width: 100%;
      padding: 0.9rem 1.1rem;
      background: linear-gradient(90deg, var(--color-primary) 70%, var(--color-accent) 100%);
      color: #fff;
      border: none;
      border-radius: 14px;
      font-size: 1.15rem;
      font-weight: 600;
      margin-top: 0.5rem;
      cursor: pointer;
      box-shadow: 0 2px 8px 0 #d2607040;
      transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
      letter-spacing: 1px;
      outline: none;
    }
    .formulario button:hover, .formulario button:focus {
      background: linear-gradient(90deg, var(--color-accent) 60%, var(--color-primary) 100%);
      transform: translateY(-2px) scale(1.03);
      box-shadow: 0 4px 16px 0 #d2607080;
    }
    .hero-details p {
      margin-top: 1rem;
      text-align: center;
      color: var(--color-primary);
      font-size: 1rem;
      opacity: 0.85;
    }
    .hero-details a {
      color: var(--color-accent);
      text-decoration: underline;
      font-weight: 500;
      transition: color var(--transition);
    }
    .hero-details a:hover {
      color: var(--color-primary);
      text-decoration: none;
    }
    @media (max-width: 600px) {
      .section-content {
        padding: 1.2rem 0.3rem;
        max-width: 98vw;
      }
      .nav-logo {
        margin-left: 0.5rem;
        margin-top: 1rem;
      }
      .logo-text {
        font-size: 1.5rem;
      }
      .form-bg {
        padding: 1rem 0.3rem 1rem 0.3rem;
      }
      .hero-details .title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <nav class="nav-bar">
      <a href="index.php" class="nav-logo">
        <h2 class="logo-text">La doceria</h2>
      </a>
    </nav>
  </header>
  <main>
    <div class="center-container">
      <section class="hero-section">
        <div class="section-content">
          <div class="hero-details">
            <h2 class="title">Entrar</h2>
            <div class="form-bg">
              <form class="formulario" method="post" action="login.php">
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit" class="button complete-order">Entrar</button>
              </form>
            </div>
            <p>Não tem conta? <a href="registro.php">Registrar</a></p>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>