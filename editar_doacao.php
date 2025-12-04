<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM doacoes WHERE id = :id AND id_doador = :doador";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':doador' => $_SESSION['usuario_id']
]);
$doacao = $stmt->fetch();

if (!$doacao) {
    die("Doação não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Doação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header"><h4>Editar Doação</h4></div>

        <div class="card-body">
            <form action="editar_doacao_action.php" method="POST">

                <input type="hidden" name="id" value="<?= $doacao['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Tipo de Alimento</label>
                    <input type="text" name="tipo_alimento" class="form-control"
                           value="<?= $doacao['tipo_alimento'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control"><?= $doacao['descricao'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantidade</label>
                    <input type="text" name="quantidade" class="form-control"
                           value="<?= $doacao['quantidade'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Validade</label>
                    <input type="date" name="validade" class="form-control"
                           value="<?= $doacao['validade'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço para Retirada</label>
                    <input type="text" name="endereco_retirada" class="form-control"
                           value="<?= $doacao['endereco_retirada'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Horário</label>
                    <input type="text" name="horario" class="form-control"
                           value="<?= $doacao['horario'] ?>" required>
                </div>

                <button class="btn btn-primary w-100">Salvar Alterações</button>

            </form>
        </div>
    </div>
</div>

</body>
</html>
