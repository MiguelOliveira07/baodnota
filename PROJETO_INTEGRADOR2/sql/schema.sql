PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  ra TEXT UNIQUE,
  senha_hash TEXT NOT NULL,
  tipo TEXT NOT NULL CHECK (tipo IN ('aluno', 'monitor', 'professor')),
  ativo INTEGER NOT NULL DEFAULT 1,
  criado_em TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS turmas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  codigo TEXT NOT NULL UNIQUE,
  curso TEXT NOT NULL,
  periodo TEXT
);

CREATE TABLE IF NOT EXISTS usuario_turma (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario_id INTEGER NOT NULL,
  turma_id INTEGER NOT NULL,
  papel TEXT NOT NULL CHECK (papel IN ('aluno', 'professor', 'monitor')),
  UNIQUE (usuario_id, turma_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS historico_monitoria (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  aluno_id INTEGER NOT NULL,
  professor_id INTEGER NOT NULL,
  acao TEXT NOT NULL CHECK (acao IN ('promovido', 'removido')),
  observacao TEXT,
  criado_em TEXT NOT NULL DEFAULT (datetime('now')),
  FOREIGN KEY (aluno_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (professor_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_usuario_turma_turma_id ON usuario_turma(turma_id);
CREATE INDEX IF NOT EXISTS idx_usuario_turma_usuario_id ON usuario_turma(usuario_id);

INSERT INTO turmas (codigo, curso, periodo) VALUES
  ('T01', 'Desenvolvimento de Sistemas', 'Noite'),
  ('T02', 'Tecnico em Informatica', 'Noite')
ON CONFLICT(codigo) DO NOTHING;

INSERT INTO usuarios (nome, email, ra, senha_hash, tipo) VALUES
  ('Professor 1', 'professor1@senac.local', NULL, '$2y$12$DtdqDiIyRO4cGxkmlxbOyuqdU/mTIcsheWtXXr60YBN9K4KItX8NW', 'professor'),
  ('Professor 2', 'professor2@senac.local', NULL, '$2y$12$DtdqDiIyRO4cGxkmlxbOyuqdU/mTIcsheWtXXr60YBN9K4KItX8NW', 'professor')
ON CONFLICT(email) DO NOTHING;

INSERT INTO usuario_turma (usuario_id, turma_id, papel)
SELECT u.id, t.id, 'professor'
FROM usuarios u
JOIN turmas t ON t.codigo = 'T01'
WHERE u.email IN ('professor1@senac.local', 'professor2@senac.local')
ON CONFLICT(usuario_id, turma_id) DO NOTHING;
