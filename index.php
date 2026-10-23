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
        <td><?= e($livro['gênero']) ?></td>
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
<?php
// Se o usuário clicou em um título, este bloco será executado
if (isset($_GET['ver_capa'])) {
    $id_livro = (int)$_GET['ver_capa'];
    
    // Procura o livro clicado dentro do seu array $livros para pegar a capa
    $livro_encontrado = null;
    foreach ($livros as $l) {
        if ($l['id'] == $id_livro) {
            $livro_encontrado = $l;
            break;
        }
    }

    if ($livro_encontrado) {
        // Define uma imagem padrão caso o livro não tenha capa cadastrada
        $nome_capa = !empty($livro_encontrado['capa']) ? $livro_encontrado['capa'] : 'sem-capa.jpg';
        ?>
        <!-- Bloco que exibe a capa na tela -->
        <div style="background: #1e1e1e; padding: 15px; border: 1px solid #333; text-align: center; margin-bottom: 20px; border-radius: 5px;">
            <a href="index.php" style="color: #ff4444; float: right; text-decoration: none; font-weight: bold;">[Fechar X]</a>
            <h3 style="margin: 0 0 10px 0; color: #fff;"><?= e($livro_encontrado['titulo']) ?></h3>
            <img src="capas/<?= $nome_capa ?>" alt="Capa" style="max-width: 180px; height: auto; border-radius: 4px;">
        </div>
        <?php
    }
}
?>