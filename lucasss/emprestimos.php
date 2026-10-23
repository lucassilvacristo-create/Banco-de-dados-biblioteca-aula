<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['devolver'])) {
        // Devolução: preenche a data de devolução com a data de hoje
        $consulta = $pdo->prepare("UPDATE emprestimos SET data_devolucao = CURDATE() WHERE id = :id");
        $consulta->execute([':id' => (int)$_POST['devolver']]);
        $msg = 'Devolução registrada.';
    } else {
        // Novo empréstimo
        $consulta = $pdo->prepare("INSERT INTO emprestimos (livro_id, aluno_id, data_emprestimo)
                                   VALUES (:livro, :aluno, CURDATE())");
        $consulta->execute([':livro' => (int)$_POST['livro_id'], ':aluno' => (int)$_POST['aluno_id']]);
        $msg = 'Empréstimo registrado.';
    }
    header('Location: emprestimos.php?msg=' . urlencode($msg));
    exit;
}

// JOIN: junta cada empréstimo com o nome do aluno e o título do livro
$sql = "SELECT e.id, a.nome, a.turma, l.titulo, e.data_emprestimo, e.data_devolucao
        FROM emprestimos e
        JOIN alunos a ON a.id = e.aluno_id
        JOIN livros l ON l.id = e.livro_id
        ORDER BY e.data_devolucao IS NOT NULL, e.data_emprestimo DESC";
$emprestimos = $pdo->query($sql)->fetchAll();

$alunos = $pdo->query("SELECT id, nome, turma FROM alunos ORDER BY nome")->fetchAll();
$livros = $pdo->query("SELECT id, titulo FROM livros ORDER BY titulo")->fetchAll();

require 'topo.php';
?>
<h2>Novo empréstimo</h2>
<?php if (isset($_GET['msg'])): ?>
  <p class="aviso"><?= e($_GET['msg']) ?></p>
<?php endif; ?>
<form method="post" class="linha">
  <select name="aluno_id" required>
    <option value="">Aluno…</option>
    <?php foreach ($alunos as $a): ?>
      <option value="<?= $a['id'] ?>"><?= e($a['nome']) ?> (<?= e($a['turma']) ?>)</option>
    <?php endforeach; ?>
  </select>
  <select name="livro_id" required>
    <option value="">Livro…</option>
    <?php foreach ($livros as $l): ?>
      <option value="<?= $l['id'] ?>"><?= e($l['titulo']) ?></option>
    <?php endforeach; ?>
  </select>
  <button>Emprestar</button>
</form>

<h2>Empréstimos</h2>
<table>
  <tr><th>Aluno</th><th>Turma</th><th>Livro</th><th>Retirado em</th><th>Situação</th></tr>
  <?php foreach ($emprestimos as $emp): ?>
    <tr>
      <td><?= e($emp['nome']) ?></td>
      <td><?= e($emp['turma']) ?></td>
      <td><?= e($emp['titulo']) ?></td>
      <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
      <td>
        <?php if ($emp['data_devolucao'] === null): ?>
          <form method="post">
            <button name="devolver" value="<?= $emp['id'] ?>">Registrar devolução</button>
          </form>
        <?php else: ?>
          Devolvido em <?= date('d/m/Y', strtotime($emp['data_devolucao'])) ?>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require 'rodape.php'; ?>
            