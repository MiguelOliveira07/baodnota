<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/database.php';
require_once __DIR__ . '/../backend/auth.php';

requirePostMethod();

try {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $ra = trim((string) ($_POST['ra'] ?? ''));
    $curso = trim((string) ($_POST['curso'] ?? ''));
    $turmaCodigo = trim((string) ($_POST['turma'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    if ($nome === '' || $email === '' || $ra === '' || $curso === '' || $turmaCodigo === '' || $senha === '') {
        throw new InvalidArgumentException('Preencha todos os campos obrigatorios.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Email invalido.');
    }

    if (mb_strlen($senha) < 6) {
        throw new InvalidArgumentException('A senha deve ter ao menos 6 caracteres.');
    }

    $pdo = db();
    $pdo->beginTransaction();

    $stmtTurma = $pdo->prepare('SELECT id FROM turmas WHERE codigo = :codigo LIMIT 1');
    $stmtTurma->execute([':codigo' => $turmaCodigo]);
    $turma = $stmtTurma->fetch();

    if (!$turma) {
        $insertTurma = $pdo->prepare('INSERT INTO turmas (codigo, curso, periodo) VALUES (:codigo, :curso, :periodo)');
        $insertTurma->execute([
            ':codigo' => $turmaCodigo,
            ':curso' => $curso,
            ':periodo' => null,
        ]);
        $turmaId = (int) $pdo->lastInsertId();
    } else {
        $turmaId = (int) $turma['id'];
    }

    $insertUser = $pdo->prepare(
        'INSERT INTO usuarios (nome, email, ra, senha_hash, tipo) VALUES (:nome, :email, :ra, :senha_hash, :tipo)'
    );
    $insertUser->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':ra' => $ra,
        ':senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
        ':tipo' => 'aluno',
    ]);

    $usuarioId = (int) $pdo->lastInsertId();

    $insertVinculo = $pdo->prepare(
        'INSERT INTO usuario_turma (usuario_id, turma_id, papel) VALUES (:usuario_id, :turma_id, :papel)'
    );
    $insertVinculo->execute([
        ':usuario_id' => $usuarioId,
        ':turma_id' => $turmaId,
        ':papel' => 'aluno',
    ]);

    $pdo->commit();

    header('Location: ../index.html?cadastro=ok');
    exit;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    echo 'Erro no cadastro: ' . $e->getMessage();
}

