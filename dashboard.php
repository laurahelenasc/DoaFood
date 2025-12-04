<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../app/config/db.php';

$stmt = $pdo->prepare("SELECT nome, tipo_perfil FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Dashboard</title>

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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">Olá, <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-lg-2" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Menu</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if ($usuario['tipo_perfil'] === 'admin'): ?>
                            <a href="admin.php" class="list-group-item list-group-item-action">Painel Admin</a>
                        <?php endif; ?>
                        
                        <?php if ($usuario['tipo_perfil'] === 'doador' || $usuario['tipo_perfil'] === 'admin'): ?>
                            <a href="doar.php" class="list-group-item list-group-item-action">Fazer Doação</a>
                            <a href="listar_doacoes.php" class="list-group-item list-group-item-action">Minhas Doações</a>
                        <?php endif; ?>

                        <?php if ($usuario['tipo_perfil'] === 'receptor' || $usuario['tipo_perfil'] === 'admin'): ?>
                            <a href="doacoes_disponiveis.php" class="list-group-item list-group-item-action">Doações Disponíveis</a>
                            <a href="listar_pedidos.php" class="list-group-item list-group-item-action">Meus Pedidos</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Bem-vindo(a) ao seu Painel!</h2>
                        <p class="card-text">Seu tipo de perfil é: <span class="badge bg-success"><?php echo htmlspecialchars(ucfirst($usuario['tipo_perfil'])); ?></span></p>
                        <p>Use o menu ao lado para gerenciar suas atividades no sistema.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>
