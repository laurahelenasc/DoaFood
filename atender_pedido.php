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

if ($usuario['tipo_perfil'] !== 'doador' && $usuario['tipo_perfil'] !== 'admin') {
    header('Location: dashboard.php?error=acesso_negado');
    exit();
}

if (!isset($_GET['doacao_id'])) {
    header('Location: listar_doacoes.php');
    exit;
}

$doacao_id = $_GET['doacao_id'];

$sql_pedido = "UPDATE pedidos SET status = 'atendido' WHERE doacao_id = :id";
$stmt_pedido = $pdo->prepare($sql_pedido);
$stmt_pedido->execute([':id' => $doacao_id]);

$sql_doacao = "UPDATE doacoes SET status = 'entregue' WHERE id = :id";
$stmt_doacao = $pdo->prepare($sql_doacao);
$stmt_doacao->execute([':id' => $doacao_id]);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Atendimento</title>
    <meta http-equiv="refresh" content="3;url=listar_doacoes.php" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title text-success"><i class="bi bi-check-circle-fill"></i> Sucesso!</h2>
                        <p class="card-text">Pedido marcado como atendido e doação como entregue!</p>
                        <p class="text-muted">Você será redirecionado em 3 segundos.</p>
                        <a href="listar_doacoes.php" class="btn btn-primary">Voltar para Minhas Doações</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>