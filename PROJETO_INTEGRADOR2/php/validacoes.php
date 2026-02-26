<?php

//validando nome
function validarNome($nome) {
    // Remove espaços extras
    $nome = trim($nome);

    // Regex: apenas letras (com acento) e espaços
    if (!preg_match("/^[\p{L} ]+$/u", $nome)) {
        return false;
    }

    // Retorna em letras minúsculas
    return mb_strtolower($nome, 'UTF-8');
}


//validando e-mail
function validarEmail($email) {
    $email = trim($email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return strtolower($email);
}


//validando senha
function validarSenha($senha) {

    $temTamanho   = strlen($senha) >= 8;
    $temMaiuscula = preg_match('/[A-Z]/', $senha);
    $temMinuscula = preg_match('/[a-z]/', $senha);
    $temNumero    = preg_match('/[0-9]/', $senha);
    $temEspecial  = preg_match('/[\W]/', $senha);

    if ($temTamanho && $temMaiuscula && $temMinuscula && $temNumero && $temEspecial) {
        return true;
    }

    return false;
}


//verifica se as senhas são iguais
function confirmarSenha($senha, $confirmarSenha) {
    return $senha === $confirmarSenha;
}


//gera token de senha para cadastrar no banco
function gerarTokenSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

?>