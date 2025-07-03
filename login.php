<?php
session_start();
require_once 'db.php'; // ajuste com suas credenciais de conexão PDO

$errors         = [];
$success        = '';
$actionExecuted = $_POST['action'] ?? '';  // capturamos a ação no topo

// Processa cadastro e login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($actionExecuted === 'register') {
        // === CADASTRO ===
        $nome  = trim($_POST['nome']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha']      ?? '';

        if (!$nome || !$email || !$senha) {
            $errors[] = 'Preencha todos os campos do cadastro.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }

        if (empty($errors)) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            try {
                $stmt->execute([
                    ':nome'  => $nome,
                    ':email' => $email,
                    ':senha' => $hash
                ]);
                $success = 'Cadastro realizado com sucesso! Agora você pode fazer login.';
            } catch (PDOException $e) {
                if ($e->errorInfo[1] === 1062) {
                    $errors[] = 'E-mail já cadastrado.';
                } else {
                    $errors[] = 'Erro ao cadastrar. Tente novamente.';
                }
            }
        }
    } elseif ($actionExecuted === 'login') {
        // === LOGIN ===
        $email = trim($_POST['email-login'] ?? '');
        $senha = $_POST['senha-login']      ?? '';

        if (!$email || !$senha) {
            $errors[] = 'Preencha e-mail e senha.';
        } else {
            // Caso especial: administrador
            if ($email === 'admin@gmail.com' && $senha === 'admin') {
                header('Location: main-admin.php');
                exit;
            }

            // Usuário normal
            $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                $success = 'Login bem-sucedido!';
            } else {
                $errors[] = 'E-mail ou senha incorretos.';
            }
        }
    }
}

// Prepara variáveis para o SweetAlert2
if (!empty($success)) {
    $tipoAviso = 'success';
    $mensagem  = $success;
} elseif (!empty($errors)) {
    $tipoAviso = 'error';
    $mensagem  = implode("\n", $errors);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazer.com</title>
    <link rel="icon" href="images/logoprojetojv1.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <a href="main.php" class="btn-back">
        <i class="fa-solid fa-arrow-left btn-back__icon"></i>
    </a>

    <div class="container" id="container">
        <!-- Painel de cadastro -->
        <div class="form-container sign-up-container">
            <form method="post" action="">
                <input type="hidden" name="action" value="register">
                <h1>Crie uma conta</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>ou use seu email para se registrar</span>
                <input type="text" placeholder="Nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" />
                <input type="email" placeholder="Email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                <input type="password" placeholder="Senha" name="senha" />
                <button type="submit" class="enviar">Cadastrar-se</button>
            </form>
        </div>

        <!-- Painel de login -->
        <div class="form-container sign-in-container">
            <form method="post" action="">
                <input type="hidden" name="action" value="login">
                <h1>Login</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Ou use sua conta</span>
                <input type="email" placeholder="Email" name="email-login" required />
                <input type="password" placeholder="Senha" name="senha-login" required />
                <button type="submit" class="enviar">Login</button>
            </form>
        </div>

        <!-- Overlay panels -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Bem vindo de volta!</h1>
                    <p>Para se manter conectado conosco, faça login com suas informações pessoais</p>
                    <button class="ghost" id="signIn">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Seja bem vindo!</h1>
                    <p>Entre com suas informações pessoais e comece sua jornada conosco</p>
                    <button class="ghost" id="signUp">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/login.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (isset($tipoAviso) && isset($mensagem)): ?>
                const action = <?= json_encode($actionExecuted) ?>;
                Swal.fire({
                    title: <?= json_encode($tipoAviso === 'success' ? 'Sucesso!' : 'Ops...') ?>,
                    text: <?= json_encode($mensagem) ?>,
                    icon: <?= json_encode($tipoAviso) ?>,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    if (action === 'login' && <?= json_encode($tipoAviso) ?> === 'success') {
                        window.location.href = 'main.php';
                    } else if (action === 'register' && <?= json_encode($tipoAviso) ?> === 'success') {
                        document.getElementById('signIn').click();
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>