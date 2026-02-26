<?php
session_start();
require_once 'conexao.php';

$erros = [];
$dados = [];

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$dados['email'] = $email;

/* =========================
   VALIDAÇÕES BÁSICAS
========================= */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros['email'] = "E-mail inválido.";
}

if (empty($senha)) {
    $erros['senha'] = "Informe sua senha.";
}

/* =========================
   BUSCAR USUÁRIO
========================= */

if (empty($erros)) {

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $erros['email'] = "E-mail não cadastrado.";
    } else {

        if (!password_verify($senha, $usuario['senha'])) {
            $erros['senha'] = "Senha incorreta.";
        }
    }
}

/* =========================
   SE TIVER ERRO → VOLTA
========================= */

if (!empty($erros)) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;
    header("Location: ../pages/login.php");
    exit;
}

/* =========================
   LOGIN OK → CRIAR SESSÃO
========================= */

$_SESSION['usuario_id'] = $usuario['id_usuario'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_email'] = $usuario['email'];

header("Location: ../pages/home.html");
exit;