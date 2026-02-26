<?php
session_start();

$erros = $_SESSION['erros'] ?? [];
$dados = $_SESSION['dados'] ?? [];

unset($_SESSION['erros']);
unset($_SESSION['dados']);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" href="../imgs/logo_baodnota.jpeg" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
  <title> Bão d' Nota | Login</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="auth-screen">
  <div id="app-header"></div>

  <main class="page container auth-page">
    <section class="card auth-card">
      <header class="card-head auth-head">
        <h1>Entrar na plataforma</h1>
        <p class="muted">Bem-vindo(a) ao Bão d' Nota!</p>
      </header>

      <form class="form" action="../php/login.php" method="post">

        <label class="field">
          <span>EMAIL</span>
          <input 
            type="email" 
            name="email"
            value="<?= $dados['email'] ?? '' ?>"
            class="<?= isset($erros['email']) ? 'input-error' : '' ?>"
            required />

          <?php if(isset($erros['email'])): ?>
            <small class="erro-msg"><?= $erros['email'] ?></small>
          <?php endif; ?>
        </label>
        
        <label class="field">
          <span>SENHA</span>
          <input 
            type="password" 
            name="senha"
            class="<?= isset($erros['senha']) ? 'input-error' : '' ?>"
            required />

          <?php if(isset($erros['senha'])): ?>
            <small class="erro-msg"><?= $erros['senha'] ?></small>
          <?php endif; ?>
        </label>

        <div class="row auth-actions">
          <button class="btn" type="submit">ENTRAR</button>
          <a class="link" href="../pages/recuperar_senha.html">ESQUECI MINHA SENHA</a>
        </div>

        <hr class="sep" />

        <p class="muted">Não é cadastrado no Bão d' Nota?</p>
        <a class="btn outline" href="cadastro.php">Criar cadastro</a>

      </form>
    </section>
  </main>

  <div id="app-footer"></div>

  <script src="../js/include.js" defer></script>
  <script src="../js/app.js" defer></script>
</body>
</html>
