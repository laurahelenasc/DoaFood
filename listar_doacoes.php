<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_doador = $_SESSION['usuario_id'];

// Agora seleciona corretamente pelo id_doador (sua tabela REAL)
$sql = "SELECT * FROM doacoes WHERE id_doador = :id ORDER BY data_cadastro DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_doador]);
$doacoes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Doações</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">DoaFood</a>
    </div>
</nav>

<div class="container py-4">
    <div class="card shadow-sm">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Minhas Doações</h2>
            <a href="doar.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Nova Doação
            </a>
        </div>

        <div class="card-body">

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">Doação registrada com sucesso!</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th width="180">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (count($doacoes) > 0): ?>
                            <?php foreach ($doacoes as $d): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['tipo_alimento']) ?></td>
                                    <td><?= htmlspecialchars($d['quantidade']) ?></td>
                                    <td><?= htmlspecialchars($d['validade']) ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= htmlspecialchars($d['status']) ?>
                                        </span>
                                    </td>
                                    <td>
    <a href="editar_doacao.php?id=<?= $d['id'] ?>" 
       class="btn btn-sm btn-warning">
       <i class="bi bi-pencil-square"></i> Editar
    </a>

    <form action="excluir_doacao.php" method="POST" style="display:inline;">
        <input type="hidden" name="id" value="<?= $d['id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger"
                onclick="return confirm('Tem certeza que deseja excluir esta doação?');">
            <i class="bi bi-trash"></i> Excluir
        </button>
    </form>
</td>

                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhuma doação encontrada.</td>
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
