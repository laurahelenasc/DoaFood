<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$stmt_user = $pdo->prepare("SELECT tipo_perfil FROM usuarios WHERE id = :id");
$stmt_user->execute(['id' => $_SESSION['usuario_id']]);
$usuario = $stmt_user->fetch();

if ($usuario['tipo_perfil'] !== 'admin') {
    header('Location: dashboard.php?error=acesso_negado');
    exit();
}

$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_doacoes = $pdo->query("SELECT COUNT(*) FROM doacoes")->fetchColumn();
$total_pedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();

$usuarios = $pdo->query("SELECT id, nome, email, tipo_perfil FROM usuarios ORDER BY id DESC LIMIT 5")->fetchAll();
$doacoes = $pdo->query("SELECT d.id, d.titulo, d.status, u.nome as doador FROM doacoes d JOIN usuarios u ON d.usuario_id = u.id ORDER BY d.id DESC LIMIT 5")->fetchAll();
$pedidos = $pdo->query("SELECT p.id, p.titulo, p.status, u.nome as receptor FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Painel Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">Doações</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Painel do Administrador</h1>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Usuários</h5>
                            <p class="card-text fs-4 fw-bold"><?= $total_usuarios ?></p>
                        </div>
                        <i class="bi bi-people-fill display-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Doações</h5>
                            <p class="card-text fs-4 fw-bold"><?= $total_doacoes ?></p>
                        </div>
                        <i class="bi bi-box2-heart-fill display-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Pedidos</h5>
                            <p class="card-text fs-4 fw-bold"><?= $total_pedidos ?></p>
                        </div>
                        <i class="bi bi-patch-question-fill display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Últimos Usuários Cadastrados</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Perfil</th></tr></thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td><?= $u['id'] ?></td>
                                        <td><?= htmlspecialchars($u['nome']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($u['tipo_perfil']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Últimas Doações Registradas</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>ID</th><th>Título</th><th>Doador</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($doacoes as $d): ?>
                                    <tr>
                                        <td><?= $d['id'] ?></td>
                                        <td><?= htmlspecialchars($d['titulo']) ?></td>
                                        <td><?= htmlspecialchars($d['doador']) ?></td>
                                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($d['status']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">Últimos Pedidos Recebidos</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead><tr><th>ID</th><th>Título</th><th>Receptor</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><?= $p['id'] ?></td>
                                        <td><?= htmlspecialchars($p['titulo']) ?></td>
                                        <td><?= htmlspecialchars($p['receptor']) ?></td>
                                        <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($p['status']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>