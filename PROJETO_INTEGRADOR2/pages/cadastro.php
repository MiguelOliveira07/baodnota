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
  <title> Bão d' Nota | Cadastro</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="auth-screen">
  <div id="app-header"></div>

  <main class="page container auth-page">
    <section class="card auth-card">
      <header class="card-head auth-head">
        <h1> Cadastrar-se </h1>
        <p class="muted">Preencha os campos para se cadastrar.</p>
      </header>

      <form class="form" action="../php/cadastro.php" method="post">
        <label class="field">
          <span>NOME</span>
          <input type="text" name="nome"
            value="<?= $dados['nome'] ?? '' ?>"
            class="<?= isset($erros['nome']) ? 'input-error' : '' ?>"
            required />
            
          <?php if(isset($erros['nome'])): ?>
            <small class="erro-msg"><?= $erros['nome'] ?></small>
          <?php endif; ?>
        </label>

        <div class="form-grid">
          <label class="field">
            <span>CURSO</span>
              <select name="curso" required>
                <option value="" selected disabled hidden> Curso </option>
                <option value="tec. informatica"> Téc. Informática </option>
                <option value="tec. logistica"> Téc. Logística </option>
                <option value="tec. seguranca do trabalho"> Téc. Segurança do Trabalho </option>
                <option value="tec. enfermagem"> Téc. Enfermagem </option>
                <option value="tec. administracao"> Téc. Administração </option>
              </select>
          </label>

          <label class="field">
            <span>TURMA</span>
              <select name="turma" required>
                <option value="" selected disabled hidden> Turma </option>
                <option value="49"> 49 </option>
                <option value="15"> 15 </option>
                <option value="25"> 25 </option>
                <option value=""></option>
              </select>
          </label>
        </div>

        <label class="field">
          <span>E-MAIL</span>
          <input type="email" name="email"
            value="<?= $dados['email'] ?? '' ?>"
            class="<?= isset($erros['email']) ? 'input-error' : '' ?>"
            required />
            
          <?php if(isset($erros['email'])): ?>
            <small class="erro-msg"><?= $erros['email'] ?></small>
          <?php endif; ?>
        </label>

        <label class="field">
          <span>SENHA</span>
          <input type="password" name="senha"
            class="<?= isset($erros['senha']) ? 'input-error' : '' ?>"
            required />
            
          <?php if(isset($erros['senha'])): ?>
            <small class="erro-msg"><?= $erros['senha'] ?></small>
          <?php endif; ?>
        </label>

        <label class="field">
          <span>CONFIRMAR SENHA</span>
          <input type="password" name="confirmar_senha"
            class="<?= isset($erros['confirmar_senha']) ? 'input-error' : '' ?>"
            required />
            
          <?php if(isset($erros['confirmar_senha'])): ?>
            <small class="erro-msg"><?= $erros['confirmar_senha'] ?></small>
          <?php endif; ?>
        </label>

        <div class="row auth-actions">
          <button class="btn" type="submit">CADASTRAR</button>
          <a class="btn outline" href="../pages/login.html">LOGIN</a>
        </div>
      </form>
    </section>
  </main>

  <div id="app-footer"></div>

  <script src="../js/include.js" defer></script>
  <script src="../js/app.js" defer></script>
</body>
</html>
