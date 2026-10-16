<?php

require 'conexao.php';

$busca = $_GET['busca'] ?? '';  

$sql = "SELECT l.id, l.titulo, l.autor, l.ano, l.genero, l.quantidade,
               COUNT(e.id) AS emprestados
        FROM livros l
        LEFT JOIN emprestimos e
               ON e.livro_id = l.id AND e.data_devolucao IS NULL
        WHERE l.titulo LIKE :busca OR l.autor LIKE :busca2
        GROUP BY l.id, l.titulo, l.autor, l.ano, l.genero, l.quantidade
        ORDER BY l.titulo";

$consulta = $pdo->prepare($sql); 
$consulta->execute([':busca' => "%$busca%", ':busca2' => "%$busca%"]);
$livros = $consulta->fetchAll();

require 'topo.php';
?>
<form method="get" class="busca">
  <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Buscar por título ou autor">
  <button>Buscar</button>
</form>

<?php if (isset($_GET['msg'])): ?>
  <p class="aviso"><?= e($_GET['msg']) ?></p>
<?php endif; ?>

<table>
  <tr><th>Título</th><th>Autor</th><th>Ano</th><th>Gênero</th><th>Disponíveis</th><th></th></tr>
  <?php foreach ($livros as $livro): ?>
    <tr>
      <td><?= e($livro['titulo']) ?></td>
      <td><?= e($livro['autor']) ?></td>
      <td><?= e($livro['ano']) ?></td>
      <td><?= e($livro['genero']) ?></td>
      <td><?= $livro['quantidade'] - $livro['emprestados'] ?> de <?= $livro['quantidade'] ?></td>
      <td class="acoes">
        <a href="editar.php?id=<?= $livro['id'] ?>">Editar</a>
        <form method="post" action="excluir.php" onsubmit="return confirm('Excluir este livro?')">
          <input type="hidden" name="id" value="<?= $livro['id'] ?>">
          <button class="perigo">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<p class="total"><?= count($livros) ?> livro(s) encontrado(s).</p>
<?php require 'rodape.php'; ?>
