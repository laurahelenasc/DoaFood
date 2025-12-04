<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Doação</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-box-seam"></i> Registrar Doação
                    </h4>
                </div>

                <div class="card-body">
                    <form action="doar_action.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Alimento</label>
                            <input type="text" name="tipo_alimento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantidade</label>
                            <input type="text" name="quantidade" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Validade</label>
                            <input type="date" name="validade" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endereço para Retirada</label>
                            <input type="text" name="endereco_retirada" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Horário Disponível</label>
                            <input type="text" name="horario" class="form-control" required placeholder="Ex: 14h às 18h">
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Registrar Doação
                        </button>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
