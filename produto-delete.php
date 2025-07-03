<?php
require_once 'db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$id]);
header('Location: produtos.php');
exit;
