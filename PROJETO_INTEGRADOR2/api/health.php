<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

echo "PHP: " . PHP_VERSION . "\n";
echo "pdo_sqlite: " . (extension_loaded('pdo_sqlite') ? 'OK' : 'NAO CARREGADA') . "\n";
echo "sqlite3: " . (extension_loaded('sqlite3') ? 'OK' : 'NAO CARREGADA') . "\n";

