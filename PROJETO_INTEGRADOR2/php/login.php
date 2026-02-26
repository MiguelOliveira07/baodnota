<?php
session_start();
require_once 'conexao.php';

$erros = [];
$dados = [];

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$dados['email'] = $email;

// validação básica
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros['email'] = "E-mail inválido.";
}

if (empty($senha)) {
    $erros['senha'] = "Informe a senha.";
}

// se o usuário está cadastrado
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

if (!empty($erros)) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;
    header("Location: ../pages/home.html");
    exit;
}

exit;
?>