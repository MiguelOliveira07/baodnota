# Integracao SQLite + PHP

## 1) Habilitar SQLite no PHP
No arquivo `php.ini`, habilite:

```
extension=pdo_sqlite
extension=sqlite3
```

Depois reinicie o servidor PHP/Apache/XAMPP.

Para validar, acesse:

`/PROJETO_INTEGRADOR2/api/health.php`

Esperado:
- `pdo_sqlite: OK`
- `sqlite3: OK`

## 2) Criar estrutura do banco
Voce pode usar duas formas:

1. Pelo navegador:
- Acesse `/PROJETO_INTEGRADOR2/api/init_db.php`

2. Pelo DB Browser:
- Crie/abra `PROJETO_INTEGRADOR2/data/monitoria.db`
- Aba `Execute SQL`
- Cole o conteudo de `PROJETO_INTEGRADOR2/sql/schema.sql`
- Execute e salve.

## 3) Fluxos integrados
- Cadastro: `cadastro.html` -> `api/register.php`
- Login: `index.html` -> `api/login.php`
- Promover monitor: `subordinados.html` -> `api/promote_monitor.php`
- Remover monitor: `subordinados.html` -> `api/demote_monitor.php`
- Sair: `api/logout.php`

## 4) Credenciais seed
- `professor1@senac.local` / `123456`
- `professor2@senac.local` / `123456`

