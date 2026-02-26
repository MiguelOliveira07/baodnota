<?php
session_start();
require_once 'validacoes.php';
require_once 'conexao.php';

$erros = [];
$dados = [];

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';
$cd_curso = $_POST['curso'] ?? '';
$cd_turma = $_POST['turma'] ?? '';

$dados['nome'] = $nome;
$dados['email'] = $email;

/* =========================
   VALIDAÇÕES
========================= */

if (!validarNome($nome)) {
    $erros['nome'] = "Nome inválido.";
}

if (!validarEmail($email)) {
    $erros['email'] = "Email inválido.";
}

if (!validarSenha($senha)) {
    $erros['senha'] = "Senha deve conter maiúscula, minúscula, número e caractere especial.";
}

if ($senha !== $confirmar) {
    $erros['confirmar_senha'] = "As senhas não coincidem.";
}

/* =========================
   VERIFICAR EMAIL NO BANCO
========================= */

if (empty($erros)) {

    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $erros['email'] = "Este e-mail já está cadastrado.";
    }
}


/* =========================
   SE TIVER ERRO → VOLTA
========================= */

if (!empty($erros)) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;
    header("Location: ../pages/cadastro.php");
    exit;
}

/* =========================
   CADASTRAR NO BANCO
========================= */

$senhaHash = gerarTokenSenha($senha);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (nome, email, senha, cd_curso, cd_turma)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    mb_strtolower($nome, 'UTF-8'),
    strtolower($email),
    $senhaHash,
    $cd_curso,
    $cd_turma
]);

/* =========================
   REDIRECIONAR
========================= */

header("Location: ../pages/home.html");
exit;