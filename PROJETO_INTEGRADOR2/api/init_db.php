<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/database.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = db();
    $schemaPath = __DIR__ . '/../sql/schema.sql';
    $schemaSql = file_get_contents($schemaPath);

    if ($schemaSql === false) {
        throw new RuntimeException('Nao foi possivel ler o arquivo de schema.');
    }

    $pdo->exec($schemaSql);
    echo "Banco inicializado com sucesso.\n";
    echo "Professores seed: professor1@senac.local e professor2@senac.local\n";
    echo "Senha padrao seed: 123456\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "Erro ao inicializar banco: " . $e->getMessage();
}

