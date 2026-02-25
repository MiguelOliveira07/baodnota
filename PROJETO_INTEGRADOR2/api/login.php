<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/database.php';
require_once __DIR__ . '/../backend/auth.php';

requirePostMethod();

try {
    $login = mb_strtolower(trim((string) ($_POST['login'] ?? '')));
    $senha = (string) ($_POST['senha'] ?? '');

    if ($login === '' || $senha === '') {
        throw new InvalidArgumentException('Informe login e senha.');
    }

    $pdo = db();
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, ra, senha_hash, tipo, ativo
         FROM usuarios
         WHERE email = :login OR ra = :login
         LIMIT 1'
    );
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch();

    if (!$user || (int) $user['ativo'] !== 1 || !password_verify($senha, (string) $user['senha_hash'])) {
        throw new RuntimeException('Credenciais invalidas.');
    }

    setLoggedUser($user);

    $target = 'home.html';
    if ($user['tipo'] === 'professor') {
        $target = 'professor.html';
    } elseif ($user['tipo'] === 'monitor') {
        $target = 'usuario.html';
    } elseif ($user['tipo'] === 'aluno') {
        $target = 'monitor_detalhe.html';
    }

    header('Location: ../' . $target);
    exit;
} catch (Throwable $e) {
    http_response_code(401);
    echo 'Erro no login: ' . $e->getMessage();
}

