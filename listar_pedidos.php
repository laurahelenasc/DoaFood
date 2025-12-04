<?php
session_start();
require_once '../app/config/db.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Busca o perfil do usuário para garantir acesso
$stmt_user = $pdo->prepare("SELECT tipo_perfil FROM usuarios WHERE id = :id");
$stmt_user->execute(['id' => $_SESSION['usuario_id']]);
$usuario = $stmt_user->fetch();

if ($usuario['tipo_perfil'] !== 'receptor' && $usuario['tipo_perfil'] !== 'admin') {
    // Apenas receptores e admins podem ver os pedidos
    header('Location: dashboard.php?error=acesso_negado');
    exit();
}

$sql = "SELECT * FROM pedidos WHERE usuario_id = :id ORDER BY data_pedido DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$pedidos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Meus Pedidos</title>

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
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">Meus Pedidos de Doação</h2>
                <a href="novo_pedido.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Novo Pedido</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Título</th>
                                <th>Categoria</th>
                                <th>Quantidade Desejada</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($pedidos) > 0): ?>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['titulo']) ?></td>
                                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                                        <td><?= htmlspecialchars($p['quantidade']) ?></td>
                                        <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($p['status']) ?></span></td>
                                        <td>
                                            <a href="editar_pedido.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                                            <a href="excluir_pedido.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum pedido encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>