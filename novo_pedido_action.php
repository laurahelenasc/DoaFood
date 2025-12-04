<?php
session_start();
require_once '../app/config/db.php';

// =====================================
// 1) Segurança – precisa estar logado
// =====================================
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// =====================================
// 2) Validar POST obrigatório
// =====================================
$camposObrigatorios = [
    'titulo',
    'categoria',
    'quantidade'
];

foreach ($camposObrigatorios as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        header("Location: novo_pedido.php?erro=campo_vazio");
        exit();
    }
}

// Sanitização simples (opcional)
$titulo     = trim($_POST['titulo']);
$descricao  = trim($_POST['descricao'] ?? '');
$categoria  = trim($_POST['categoria']);
$quantidade = (int) $_POST['quantidade'];
$usuario_id = (int) $_SESSION['usuario_id'];

// =====================================
// 3) Insert conforme schema do PostgreSQL
// =====================================
$sql = "
    INSERT INTO pedidos 
        (usuario_id, titulo, descricao, categoria, quantidade)
    VALUES 
        (:uid, :t, :d, :c, :q)
";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':uid' => $usuario_id,
        ':t'  => $titulo,
        ':d'  => $descricao,
        ':c'  => $categoria,
        ':q'  => $quantidade
    ]);

    $success = true;

} catch (PDOException $e) {
    $erro = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema - Pedido</title>
    <meta http-equiv="refresh" content="3;url=listar_pedidos.php" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body text-center">

                    <?php if (isset($success)): ?>
                        <h2 class="text-success">
                            <i class="bi bi-check-circle-fill"></i> Sucesso!
                        </h2>
                        <p>Pedido registrado com sucesso.</p>

                    <?php else: ?>
                        <h2 class="text-danger">
                            <i class="bi bi-x-circle-fill"></i> Erro!
                        </h2>
                        <p>Não foi possível registrar o pedido.</p>

                        <div class="alert alert-warning mt-3">
                            <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-muted mt-3">
                        Você será redirecionado em 3 segundos...
                    </p>

                    <a href="listar_pedidos.php" class="btn btn-primary">Ver Meus Pedidos</a>
                    <a href="novo_pedido.php" class="btn btn-secondary">Novo Pedido</a>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
