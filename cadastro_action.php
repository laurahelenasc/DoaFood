<?php
require_once '../app/config/db.php';

$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$perfil = $_POST['tipo_perfil'];

$sql = "INSERT INTO usuarios (nome, email, senha, tipo_perfil)
        VALUES (:nome, :email, :senha, :perfil)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':senha' => $senha,
    ':perfil' => $perfil
]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Doação de Alimentos - Sucesso</title>

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
                        <p class="card-text">Usuário cadastrado com sucesso!</p>
                        <a href="login.php" class="btn btn-primary">Ir para o Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>