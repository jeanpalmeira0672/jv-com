<?php
// produto-form.php
require_once 'db.php';
session_start();

// só deixa passar se estiver logado **e** for admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
$produto = ['nome' => '', 'preco' => '', 'estoque' => '', 'categoria' => '', 'imagem' => '', 'descricao' => '', 'video' => ''];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // coleta dados do formulário
    $produto['nome']      = trim($_POST['nome'] ?? '');
    $produto['preco']     = trim($_POST['preco'] ?? '');
    $produto['estoque']   = trim($_POST['estoque'] ?? '');
    $produto['categoria'] = trim($_POST['categoria'] ?? '');
    $produto['descricao'] = trim($_POST['descricao'] ?? '');
    $produto['video']     = trim($_POST['video'] ?? '');

    // validações básicas
    if ($produto['nome'] === '') $errors[] = 'Nome obrigatório.';
    if (!is_numeric($produto['preco'])) $errors[] = 'Preço inválido.';
    if (!is_numeric($produto['estoque'])) $errors[] = 'Estoque inválido.';

    // upload de imagem
    if (!empty($_FILES['imagem']['name'])) {
        $file     = $_FILES['imagem'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro no upload de imagem.';
        } elseif (!in_array($ext, $allowed)) {
            $errors[] = 'Formato de imagem não permitido.';
        } else {
            if (!is_dir(__DIR__ . '/uploads')) {
                mkdir(__DIR__ . '/uploads', 0755, true);
            }
            $newName = uniqid() . ".{$ext}";
            $dest    = __DIR__ . '/uploads/' . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $produto['imagem'] = $newName;
            } else {
                $errors[] = 'Falha ao salvar imagem.';
            }
        }
    }

    if (empty($errors)) {
        if ($id) {
            // atualiza produto
            $sql = "UPDATE produtos SET nome = ?, preco = ?, estoque = ?, categoria = ?, descricao = ?, video = ?";
            if ($produto['imagem']) {
                $sql .= ", imagem = ?";
            }
            $sql .= " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $params = [$produto['nome'], $produto['preco'], $produto['estoque'], $produto['categoria'], $produto['descricao'], $produto['video']];
            if ($produto['imagem']) {
                $params[] = $produto['imagem'];
            }
            $params[] = $id;
            $stmt->execute($params);
            $success = 'Produto atualizado com sucesso.';
        } else {
            // insere novo produto
            $sql = "INSERT INTO produtos (nome, preco, estoque, categoria, descricao, video, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $produto['nome'],
                $produto['preco'],
                $produto['estoque'],
                $produto['categoria'],
                $produto['descricao'],
                $produto['video'],
                $produto['imagem'],
            ]);
            $success = 'Produto criado com sucesso.';
        }
        // redireciona para lista
        header('Location: produtos.php');
        exit;
    }
}

if ($id && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // busca dados existentes
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC) ?: $produto;
}
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
    <a href="produtos.php" class="btn-back">
        <i class="fa-solid fa-arrow-left btn-back__icon"></i>
    </a>
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
                <label>Descrição</label>
                <textarea name="descricao" value="<?= $produto['descricao'] ?>" required></textarea>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <select name="categoria" id="categorias">
                    <option value="gamer" <?= ($produto['categoria'] == 'gamer') ? 'selected' : '' ?>>Gamer</option>
                    <option value="2 em 1" <?= ($produto['categoria'] == '2 em 1') ? 'selected' : '' ?>>2 em 1</option>
                    <option value="premium" <?= ($produto['categoria'] == 'premium') ? 'selected' : '' ?>>Premium</option>
                    <option value="custo-benefício" <?= ($produto['categoria'] == 'custo-benefício') ? 'selected' : '' ?>>Custo Benefício</option>
                    <option value="estudos" <?= ($produto['categoria'] == 'estudos') ? 'selected' : '' ?>>Estudos</option>
                </select>

            </div>
            <div class="form-group">
                <label>Imagem <?= $id ? '(opcional)' : '' ?></label>
                <input type="file" name="imagem" <?= $id ? '' : 'required' ?>>
                <?php if (!empty($produto['imagem'])): ?>
                    <p>Imagem atual:</p>
                    <img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem atual" style="max-width: 150px;">
                <?php endif; ?>
                <div class="form-group">
                    <label>Video</label>
                    <input type="text" name="video" value="<?= ($produto['video']) ?>" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $id ? 'Atualizar' : 'Criar' ?></button>
                <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>