<?php
session_start();
require_once '../app/config/db.php';

// ====================================
// Segurança / Autenticação
// ====================================
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = (int) $_SESSION['usuario_id'];

// ====================================
// Checa perfil
// ====================================
$stmt_user = $pdo->prepare("SELECT tipo_perfil FROM usuarios WHERE id = :id LIMIT 1");
$stmt_user->execute([':id' => $usuario_id]);
$usuario = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$usuario || ($usuario['tipo_perfil'] !== 'receptor' && $usuario['tipo_perfil'] !== 'admin')) {
    header("Location: dashboard.php?erro=acesso");
    exit();
}

// ====================================
// Checa GET
// ====================================
if (!isset($_GET['doacao_id']) || !is_numeric($_GET['doacao_id'])) {
    header("Location: doacoes_disponiveis.php");
    exit();
}

$doacao_id = (int) $_GET['doacao_id'];


// ====================================
// Transação para evitar inconsistências
// ====================================
$pdo->beginTransaction();

try {

    // ====================================
    // 1) Atualiza status da doação
    // ====================================
    $sql_doacao = "
        UPDATE doacoes
        SET status = 'reservado'
        WHERE id = :id AND status = 'disponivel'
    ";
    $stmt_doacao = $pdo->prepare($sql_doacao);
    $stmt_doacao->execute([':id' => $doacao_id]);

    if ($stmt_doacao->rowCount() === 0) {
        throw new Exception("Não foi possível solicitar esta doação (possivelmente já reservada).");
    }

    // ====================================
    // 2) Encontra pedido pendente mais antigo
    // ====================================
    $sql_pedido = "
        SELECT id
        FROM pedidos
        WHERE usuario_id = :uid AND status = 'pendente'
        ORDER BY data_pedido ASC
        LIMIT 1
    ";
    $stmt_pedido = $pdo->prepare($sql_pedido);
    $stmt_pedido->execute([':uid' => $usuario_id]);
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        throw new Exception("Você não possui pedidos pendentes para vincular.");
    }

    // ====================================
    // 3) Vincula doação ao pedido
    // ====================================
    $sql_atualiza = "
        UPDATE pedidos
        SET doacao_id = :did, status = 'atendido'
        WHERE id = :pid
    ";
    $stmt_vincular = $pdo->prepare($sql_atualiza);
    $stmt_vincular->execute([
        ':did' => $doacao_id,
        ':pid' => $pedido['id']
    ]);

    $pdo->commit();
    $success_message = "Doação solicitada com sucesso!";

} catch (Exception $e) {
    $pdo->rollBack();
    $error_message = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Doação</title>
    <meta http-equiv="refresh" content="4;url=listar_pedidos.php" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body text-center">

                    <?php if (isset($success_message)): ?>
                        <h2 class="text-success"><i class="bi bi-check-circle-fill"></i> Sucesso!</h2>
                        <p><?= $success_message ?></p>
                    <?php else: ?>
                        <h2 class="text-danger"><i class="bi bi-x-circle-fill"></i> Erro!</h2>
                        <p><?= $error_message ?></p>
                    <?php endif; ?>

                    <p class="text-muted">Você será redirecionado em 4 segundos.</p>

                    <a href="listar_pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
                    <a href="doacoes_disponiveis.php" class="btn btn-secondary">Voltar</a>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
