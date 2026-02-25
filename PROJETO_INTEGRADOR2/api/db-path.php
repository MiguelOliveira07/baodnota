<?php
require_once __DIR__ . '/../backend/database.php';

$pdo = db();

echo "Caminho esperado do banco:\n";
echo __DIR__ . '/../data/monitoria.db' . "\n\n";

echo "Existe?\n";
echo file_exists(__DIR__ . '/../data/monitoria.db') ? "SIM" : "NAO";