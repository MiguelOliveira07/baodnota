<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/database.php';
require_once __DIR__ . '/../backend/auth.php';

requirePostMethod();
$professor = requireRole('professor');

try {
    $monitorEmail = mb_strtolower(trim((string) ($_POST['monitor_email'] ?? '')));
    $observacao = trim((string) ($_POST['observacao'] ?? ''));

    if ($monitorEmail === '' || !filter_var($monitorEmail, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Informe um email valido do monitor.');
    }

    $pdo = db();
    $pdo->beginTransaction();

    $stmtMonitor = $pdo->prepare('SELECT id, tipo FROM usuarios WHERE email = :email LIMIT 1');
    $stmtMonitor->execute([':email' => $monitorEmail]);
    $monitor = $stmtMonitor->fetch();

    if (!$monitor) {
        throw new RuntimeException('Usuario nao encontrado.');
    }

    $monitorId = (int) $monitor['id'];
    $tipoAtual = (string) $monitor['tipo'];

    if ($tipoAtual !== 'monitor') {
        throw new RuntimeException('Usuario informado nao e monitor.');
    }

    $update = $pdo->prepare('UPDATE usuarios SET tipo = :tipo WHERE id = :id');
    $update->execute([
        ':tipo' => 'aluno',
        ':id' => $monitorId,
    ]);

    $updateVinculo = $pdo->prepare(
        "UPDATE usuario_turma SET papel = 'aluno' WHERE usuario_id = :usuario_id AND papel = 'monitor'"
    );
    $updateVinculo->execute([':usuario_id' => $monitorId]);

    $log = $pdo->prepare(
        'INSERT INTO historico_monitoria (aluno_id, professor_id, acao, observacao)
         VALUES (:aluno_id, :professor_id, :acao, :observacao)'
    );
    $log->execute([
        ':aluno_id' => $monitorId,
        ':professor_id' => (int) $professor['id'],
        ':acao' => 'removido',
        ':observacao' => $observacao !== '' ? $observacao : null,
    ]);

    $pdo->commit();

    header('Location: ../subordinados.html?acao=removido');
    exit;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(400);
    echo 'Erro ao remover monitor: ' . $e->getMessage();
}

