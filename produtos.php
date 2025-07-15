<?php
require_once 'db.php';
session_start();

// só deixa passar se estiver logado **e** for admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
$produtos = $pdo->query("SELECT * FROM produtos")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>FéNotebooks.com - Produtos</title>
    <link rel="stylesheet" href="css/crud2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="images/logo.png">
</head>

<body class="page-loaded">
    <a href="main-admin.php" class="btn-back">
        <i class="fa-solid fa-arrow-left btn-back__icon"></i>
    </a>
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
                    <th>categoria</th>
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
                        <td data-label="Categoria"><?= $p['categoria'] ?></td>
                        <td data-label="Ações">
                            <a href="produto-form.php?id=<?= $p['id'] ?>" class="btn btn-secondary">Editar</a>
                            <a href="produto-delete.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-delete">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = btn.getAttribute('href');

                    Swal.fire({
                        title: 'Tem certeza?',
                        text: 'Tem certeza que deseja excluir este produto?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Cancelar',
                        focusCancel: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>