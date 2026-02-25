<?php
session_start();
require_once 'validacoes.php';

$erros = [];
$dados = [];

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';

$dados['nome'] = $nome;
$dados['email'] = $email;

/* VALIDAR NOME */
if (!validarNome($nome)) {
    $erros['nome'] = "Nome inválido. Apenas letras são permitidas.";
    $dados['nome'] = '';
}

/* VALIDAR EMAIL */
if (!validarEmail($email)) {
    $erros['email'] = "E-mail inválido.";
    $dados['email'] = '';
}

/* VALIDAR SENHA */
if (!validarSenha($senha)) {
    $erros['senha'] = "A senha deve conter maiúscula, minúscula, número e caractere especial.";
}

/* CONFIRMAR SENHA */
if ($senha !== $confirmar) {
    $erros['confirmar_senha'] = "As senhas não coincidem.";
}

/* SE TIVER ERRO → VOLTA */
if (!empty($erros)) {
    $_SESSION['erros'] = $erros;
    $_SESSION['dados'] = $dados;

    header("Location: ../pages/cadastro.php");
    exit;
}

/* SE NÃO TIVER ERRO */
$token = gerarTokenSenha($senha);

echo "Cadastro válido!";

?>

