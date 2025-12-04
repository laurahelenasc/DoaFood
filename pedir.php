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
    // Apenas receptores e admins podem criar pedidos
    header('Location: dashboard.php?error=acesso_negado');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Fazer Pedido</title>

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
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="h4 mb-0">Fazer um Pedido de Doação</h2>
                    </div>
                    <div class="card-body">
                        <form action="pedir_action.php" method="POST">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título do Pedido:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ex: Cesta básica" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição detalhada:</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descreva os itens que você mais precisa."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="categoria" class="form-label">Categoria:</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option value="alimento" selected>Alimento</option>
                                        <option value="higiene">Higiene</option>
                                        <option value="roupa">Roupa</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quantidade" class="form-label">Quantidade/Unidade:</label>
                                    <input type="text" class="form-control" id="quantidade" name="quantidade" placeholder="Ex: 1 cesta, 5kg, 3 pacotes" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="listar_pedidos.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Enviar Pedido</button>
                            </div>
                        </form>
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