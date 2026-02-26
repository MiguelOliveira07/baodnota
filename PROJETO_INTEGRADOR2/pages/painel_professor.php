<?php
session_start();
require_once '../php/conexao.php';

/* =========================
   PROTEÇÃO DE ACESSO
========================= */
if (!isset($_SESSION['usuario_id']) || !$_SESSION['is_professor']) {
    header("Location: home.php");
    exit;
}

/* =========================
   DADOS DO PROFESSOR
========================= */
$stmt = $pdo->prepare("SELECT nome, email FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   LISTA DE MONITORES
========================= */
$stmt = $pdo->query("
    SELECT nome, email 
    FROM usuarios 
    WHERE monitor = 'sim'
    ORDER BY nome
");
$monitores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
  <title>Bão d' Nota | Professores</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
  <div id="app-header"></div>

  <main class="page container">
    <section class="card panel-main">
      <header class="card-head">
        <h1>Meu Perfil</h1>
        <p class="muted">Gerencie seus monitores.</p>
      </header>

      <div class="profile-grid">

        <!-- LADO ESQUERDO -->
        <aside class="profile-side">

          <!-- INFO PROFESSOR -->
          <article class="card info-card">
            <h2>Informações</h2>

            <label class="field">
              <span>Nome</span>
              <input type="text" value="<?= htmlspecialchars($professor['nome']) ?>" readonly />
            </label>

            <label class="field">
              <span>Email</span>
              <input type="text" value="<?= htmlspecialchars($professor['email']) ?>" readonly />
            </label>
          </article>

          <!-- GERENCIAR MONITOR -->
          <article class="card info-card">
            <h2>Promover / Remover Monitor</h2>

            <form action="../php/alterar_monitor.php" method="POST" class="form">

              <label class="field">
                <span>Email do usuário</span>
                <input type="email" name="email" required>
              </label>

              <label class="field">
                <span>Ação</span>
                <select name="acao" required>
                  <option value="sim">Tornar Monitor</option>
                  <option value="nao">Remover Monitor</option>
                </select>
              </label>

              <button class="btn" type="submit">Atualizar</button>

            </form>
          </article>

        </aside>

        <!-- LADO DIREITO -->
        <div class="profile-content">
          <article class="card panel-box">
            <h2>Monitores Ativos</h2>

            <?php if (!empty($monitores)): ?>
              <ul class="plain-list">
                <?php foreach ($monitores as $monitor): ?>
                  <li>
                    <strong><?= htmlspecialchars($monitor['nome']) ?></strong><br>
                    <small><?= htmlspecialchars($monitor['email']) ?></small>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Nenhum monitor cadastrado.</p>
            <?php endif; ?>

          </article>
        </div>

      </div>
    </section>
  </main>

  <div id="app-footer"></div>
</body>
</html>