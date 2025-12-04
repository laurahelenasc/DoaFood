<?php
session_start();
require_once '../app/config/db.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: listar_pedidos.php');
    exit;
}

$id = $_POST['id'];
$uid = $_SESSION['usuario_id'];

$sql = "DELETE FROM pedidos WHERE id = :id AND usuario_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':uid' => $uid]);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Sucesso</title>
    <meta http-equiv="refresh" content="3;url=listar_pedidos.php" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title text-success"><i class="bi bi-check-circle-fill"></i> Sucesso!</h2>
                        <p class="card-text">Pedido excluído com sucesso!</p>
                        <p class="text-muted">Você será redirecionado em 3 segundos.</p>
                        <a href="listar_pedidos.php" class="btn btn-primary">Voltar para Meus Pedidos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>
