<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = (int) $_SESSION['usuario_id'];


$stmt_user = $pdo->prepare("SELECT tipo_perfil FROM usuarios WHERE id = :id LIMIT 1");
$stmt_user->execute([':id' => $usuario_id]);
$usuario = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php");
    exit();
}

if ($usuario['tipo_perfil'] !== 'receptor' && $usuario['tipo_perfil'] !== 'admin') {
    header("Location: dashboard.php?erro=acesso");
    exit();
}


$sql = "
    SELECT 
        d.id,
        d.tipo_alimento,
        d.quantidade,
        u.nome AS doador
    FROM doacoes d
    JOIN usuarios u ON u.id = d.id_doador
    WHERE d.status = 'disponivel'
    ORDER BY d.data_cadastro DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponível para Mim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">Doações</a>
    </div>
</nav>

<div class="container py-4">
    <div class="card shadow-sm">

        <div class="card-header">
            <h2 class="h4 mb-0">Disponível para Mim</h2>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Alimento</th>
                            <th>Quantidade</th>
                            <th>Doador</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($lista)): ?>
                            <?php foreach ($lista as $d): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['tipo_alimento']) ?></td>
                                    <td><?= htmlspecialchars($d['quantidade']) ?></td>
                                    <td><?= htmlspecialchars($d['doador']) ?></td>
                                    <td>
                                        <a href="solicitar_doacao.php?doacao_id=<?= $d['id'] ?>" class="btn btn-sm btn-success">
                                            <i class="bi bi-hand-thumbs-up me-2"></i>Solicitar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Nenhum item disponível para mim.</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
