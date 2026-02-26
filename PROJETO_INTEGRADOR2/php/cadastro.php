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

// validações

if (!validarNome($nome)) {
    $erros['nome'] = "Nome inválido.";
}

if (!validarEmail($email)) {
    $erros['email'] = "Email inválido.";
}

if (!validarSenha($senha)) {
    $erros['senha'] = "Senha deve conter maiúscula, minúscula, número, caractere especial e possuir no mínimo 8 caracteres.";
}

if ($senha !== $confirmar) {
    $erros['confirmar_senha'] = "As senhas não coincidem.";
}

// verificar e-mail no banco

if (empty($erros)) {

    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $erros['email'] = "Este e-mail já está cadastrado.";
    }
}


// cadastrar no banco

if (!empty($erros)) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;
    header("Location: ../pages/cadastro.php");
    exit;
}

// cadastrar no banco

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

//redirecionar para a home

header("Location: ../pages/home.html");
exit;