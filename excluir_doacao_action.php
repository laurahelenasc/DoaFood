<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Garantir que veio via POST e que existe o ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: listar_doacoes.php");
    exit;
}

$id = (int) $_POST['id'];
$usuario_id = (int) $_SESSION['usuario_id'];

try {
    // Apenas exclui se a doação pertence ao usuário logado
    $sql = "DELETE FROM doacoes 
            WHERE id = :id 
            AND usuario_id = :usuario_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id
    ]);

} catch (PDOException $e) {
    // Caso queira tratar erros depois
    // echo "Erro ao excluir: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doação Excluída</title>

    <!-- Redireciona em 3 segundos -->
    <meta http-equiv="refresh" content="3;url=listar_doacoes.php" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h2 class="card-title text-success">
                            <i class="bi bi-check-circle-fill"></i> Sucesso!
                        </h2>
                        <p class="card-text fs-5">A doação foi excluída com sucesso.</p>
                        <p class="text-muted">Você será redirecionado em alguns segundos.</p>
                        <a href="listar_doacoes.php" class="btn btn-primary">Voltar para Minhas Doações</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
