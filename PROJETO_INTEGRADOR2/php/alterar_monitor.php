<?php
session_start();
require_once 'conexao.php';

/* =========================
   PROTEÇÃO
========================= */
if (!isset($_SESSION['usuario_id']) || !$_SESSION['is_professor']) {
    header("Location: ../pages/home.php");
    exit;
}

$email = $_POST['email'] ?? '';
$acao  = $_POST['acao'] ?? '';

/* =========================
   VALIDAÇÕES
========================= */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido.");
}

if (!in_array($acao, ['sim','nao'])) {
    die("Ação inválida.");
}

/* =========================
   VERIFICAR USUÁRIO
========================= */
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}

/* =========================
   ATUALIZAR MONITOR
========================= */
$stmt = $pdo->prepare("UPDATE usuarios SET monitor = ? WHERE email = ?");
$stmt->execute([$acao, $email]);

header("Location: ../pages/professor.php");
exit;