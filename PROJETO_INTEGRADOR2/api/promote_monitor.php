<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/database.php';
require_once __DIR__ . '/../backend/auth.php';

requirePostMethod();
$professor = requireRole('professor');

try {
    $alunoEmail = mb_strtolower(trim((string) ($_POST['aluno_email'] ?? '')));
    $observacao = trim((string) ($_POST['observacao'] ?? ''));

    if ($alunoEmail === '' || !filter_var($alunoEmail, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Informe um email valido do aluno.');
    }

    $pdo = db();
    $pdo->beginTransaction();

    $stmtAluno = $pdo->prepare('SELECT id, tipo FROM usuarios WHERE email = :email LIMIT 1');
    $stmtAluno->execute([':email' => $alunoEmail]);
    $aluno = $stmtAluno->fetch();

    if (!$aluno) {
        throw new RuntimeException('Aluno nao encontrado.');
    }

    $alunoId = (int) $aluno['id'];
    $tipoAtual = (string) $aluno['tipo'];

    if ($tipoAtual === 'professor') {
        throw new RuntimeException('Nao e permitido alterar papel de professor.');
    }

    if ($tipoAtual === 'monitor') {
        throw new RuntimeException('Usuario ja e monitor.');
    }

    $update = $pdo->prepare('UPDATE usuarios SET tipo = :tipo WHERE id = :id');
    $update->execute([
        ':tipo' => 'monitor',
        ':id' => $alunoId,
    ]);

    $updateVinculo = $pdo->prepare(
        "UPDATE usuario_turma SET papel = 'monitor' WHERE usuario_id = :usuario_id AND papel = 'aluno'"
    );
    $updateVinculo->execute([':usuario_id' => $alunoId]);

    $log = $pdo->prepare(
        'INSERT INTO historico_monitoria (aluno_id, professor_id, acao, observacao)
         VALUES (:aluno_id, :professor_id, :acao, :observacao)'
    );
    $log->execute([
        ':aluno_id' => $alunoId,
        ':professor_id' => (int) $professor['id'],
        ':acao' => 'promovido',
        ':observacao' => $observacao !== '' ? $observacao : null,
    ]);

    $pdo->commit();

    header('Location: ../subordinados.html?acao=promovido');
    exit;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    echo 'Erro ao promover monitor: ' . $e->getMessage();
}

