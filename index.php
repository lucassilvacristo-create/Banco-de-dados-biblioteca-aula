<?php

require 'conexao.php';

$busca = $_GET['busca'] ?? '';  

$sql = "SELECT l.id, l.titulo, l.autor, l.ano, l.genero, l.quantidade, l.capa,
               COUNT(e.id) AS emprestados
        FROM livros l
        LEFT JOIN emprestimos e
               ON e.livro_id = l.id AND e.data_devolucao IS NULL
        WHERE l.titulo LIKE :busca OR l.autor LIKE :busca2
        GROUP BY l.id, l.titulo, l.autor, l.ano, l.genero, l.quantidade, l.capa
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
<tr><th>Título</th><th>Autor</th><th>Ano</th><th>Gênero</th><th>Disponíveis</th><th></th><th></th></tr>
<?php foreach ($livros as $livro): ?>
    <tr>
        <!-- MODIFICADO: O título agora é um link que envia o ID pela URL -->
        <td>
            <a href="index.php?ver_capa=<?= $livro['id'] ?>" style="color: inherit; text-decoration: underline;">
                <?= e($livro['titulo']) ?>
            </a>
        </td>
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
</table>

<?php
if (isset($_GET['ver_capa'])) {
    $id_livro = trim($_GET['ver_capa']);
    
    $livro_encontrado = null;
    foreach ($livros as $l) {
        if (strval($l['id']) === strval($id_livro)) {
            $livro_encontrado = $l;
            break;
        }
    }

    if ($livro_encontrado) {
        // Correção da lógica que estava vazando como texto na tela
        $nome_capa = (!empty($livro_encontrado['capa'])) ? $livro_encontrado['capa'] : 'sem-capa.jpg';
        ?>
        <div style="background: #1e1e1e; padding: 15px; border: 1px solid #333; text-align: center; margin-top: 20px; border-radius: 5px; width: 100%; max-width: 400px; margin-left: auto; margin-right: auto;">
            <a href="index.php" style="color: #ff4444; float: right; text-decoration: none; font-weight: bold;">[Fechar X]</a>
            <h3 style="margin: 0 0 10px 0; color: #fff; text-align: center;"><?= e($livro_encontrado['titulo']) ?></h3>
            <img src="capas/<?= $nome_capa ?>" alt="Capa" style="max-width: 180px; height: auto; border-radius: 4px; display: block; margin: 0 auto;">
        </div>
        <?php
    }
}
?>