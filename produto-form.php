<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;
$produto = ['nome' => '', 'preco' => '', 'estoque' => '', 'imagem' => ''];
$errors = [];
$success = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        die('Produto não encontrado!');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $imagem = $_FILES['imagem'] ?? null;

    if (!$nome || !$preco || !$estoque) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    // Processa imagem
    $caminhoImagem = $produto['imagem'];

    if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
        $nomeImagem = uniqid() . '.' . $extensao;
        $destino = __DIR__ . '/uploads/' . $nomeImagem;

        if (!is_dir(__DIR__ . '/uploads')) {
            mkdir(__DIR__ . '/uploads', 0777, true);
        }

        if (move_uploaded_file($imagem['tmp_name'], $destino)) {
            $caminhoImagem = $nomeImagem;
        } else {
            $errors[] = 'Erro ao fazer upload da imagem.';
        }
    }

    if (empty($errors)) {
        if ($id) {
            // Atualizar
            $stmt = $pdo->prepare("UPDATE produtos SET nome=?, preco=?, estoque=?, imagem=? WHERE id=?");
            $stmt->execute([$nome, $preco, $estoque, $caminhoImagem, $id]);
            $success = 'Produto atualizado com sucesso!';
        } else {
            // Criar
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, estoque, imagem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $preco, $estoque, $caminhoImagem]);
            $success = 'Produto criado com sucesso!';
            $produto = ['nome' => '', 'preco' => '', 'estoque' => '', 'imagem' => ''];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Novo' ?> Produto</title>
    <link rel="stylesheet" href="css/crud2.css">
</head>

<body class="page-loaded">
    <div class="container">
        <h1 class="section__title"><?= $id ? 'Editar' : 'Novo' ?> Produto</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?= implode('<br>', $errors) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="product-form">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label>Preço</label>
                <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required>
            </div>
            <div class="form-group">
                <label>Estoque</label>
                <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" required>
            </div>
            <div class="form-group">
                <label>Imagem <?= $id ? '(opcional)' : '' ?></label>
                <input type="file" name="imagem" <?= $id ? '' : 'required' ?>>
                <?php if (!empty($produto['imagem'])): ?>
                    <p>Imagem atual:</p>
                    <img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem atual" style="max-width: 150px;">
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $id ? 'Atualizar' : 'Criar' ?></button>
                <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>