<?php
require 'conexao.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE livros
            SET titulo = :titulo, autor = :autor, ano = :ano, genero = :genero, quantidade = :quantidade
            WHERE id = :id";                  // sem o WHERE, TODOS os livros seriam alterados!
    $consulta = $pdo->prepare($sql);
    $consulta->execute([
        ':titulo'     => $_POST['titulo'],
        ':autor'      => $_POST['autor'],
        ':ano'        => $_POST['ano'] !== '' ? (int)$_POST['ano'] : null,
        ':genero'     => $_POST['genero'],
        ':quantidade' => (int)$_POST['quantidade'],
        ':id'         => $id,
    ]);
    header('Location: index.php?msg=' . urlencode('Livro atualizado!'));
    exit;
}

$consulta = $pdo->prepare("SELECT * FROM livros WHERE id = :id");
$consulta->execute([':id' => $id]);
$livro = $consulta->fetch();
if (!$livro) {
    die('Livro não encontrado.');
}

require 'topo.php';
?>
<h2>Editar livro</h2>
<form method="post" class="ficha">
  <input type="hidden" name="id" value="<?= $livro['id'] ?>">
  <label>Título <input name="titulo" value="<?= e($livro['titulo']) ?>" required maxlength="150"></label>
  <label>Autor <input name="autor" value="<?= e($livro['autor']) ?>" required maxlength="100"></label>
  <label>Ano <input name="ano" type="number" value="<?= e($livro['ano']) ?>"></label>
  <label>Gênero <input name="genero" value="<?= e($livro['genero']) ?>" maxlength="50"></label>
  <label>Quantidade <input name="quantidade" type="number" min="1" value="<?= e($livro['quantidade']) ?>" required></label>
  <button>Salvar alterações</button>
</form>
<?php require 'rodape.php'; ?>
