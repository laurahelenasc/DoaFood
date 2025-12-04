<?php
session_start();
require_once '../app/config/db.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: listar_pedidos.php');
    exit;
}

$id = $_GET['id'];
$uid = $_SESSION['usuario_id'];

$sql = "SELECT titulo FROM pedidos WHERE id = :id AND usuario_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':uid' => $uid]);

$pedido = $stmt->fetch();

if (!$pedido) {
    header('Location: listar_pedidos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Excluir Pedido</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

    <!-- NAVBAR FIXA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">Doações</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h2 class="h4 mb-0">Excluir Pedido</h2>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Tem certeza que deseja excluir o pedido abaixo?</p>
                        <h3 class="text-danger"><strong>"<?= htmlspecialchars($pedido['titulo']) ?>"</strong></h3>
                        <p class="text-muted">Esta ação não pode ser desfeita.</p>
                        
                        <form action="excluir_pedido_action.php" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill me-2"></i>Sim, excluir</button>
                        </form>
                        <a href="listar_pedidos.php" class="btn btn-secondary">Cancelar</a>
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