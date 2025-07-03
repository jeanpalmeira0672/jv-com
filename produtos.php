<?php
require_once 'db.php';
$produtos = $pdo->query("SELECT * FROM produtos")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD Produtos – Gazer</title>
    <link rel="stylesheet" href="css/crud2.css">
</head>

<body class="page-loaded">
    <div class="container">
        <h1 class="section__title">Gerenciar Produtos</h1>
        <a href="produto-form.php" class="btn btn-primary">+ Novo Produto</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td data-label="ID"><?= $p['id'] ?></td>
                        <td data-label="Nome"><?= htmlspecialchars($p['nome']) ?></td>
                        <td data-label="Preço">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                        <td data-label="Estoque"><?= $p['estoque'] ?></td>
                        <td data-label="Ações">
                            <a href="produto-form.php?id=<?= $p['id'] ?>" class="btn btn-secondary">Editar</a>
                            <a href="produto-delete.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>