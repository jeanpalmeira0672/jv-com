<?php
session_start();

// só deixa passar se estiver logado **e** for admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}
require_once 'db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$id]);
header('Location: produtos.php');
exit;
