<?php
declare(strict_types=1);

function startSessionIfNeeded(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requirePostMethod(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        echo 'Metodo nao permitido.';
        exit;
    }
}

function currentUser(): ?array
{
    startSessionIfNeeded();
    return $_SESSION['user'] ?? null;
}

function requireRole(string $role): array
{
    $user = currentUser();
    if (!$user || ($user['tipo'] ?? null) !== $role) {
        http_response_code(403);
        echo 'Acesso negado.';
        exit;
    }

    return $user;
}

function setLoggedUser(array $user): void
{
    startSessionIfNeeded();
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'nome' => (string) $user['nome'],
        'email' => (string) $user['email'],
        'ra' => (string) ($user['ra'] ?? ''),
        'tipo' => (string) $user['tipo'],
    ];
}

