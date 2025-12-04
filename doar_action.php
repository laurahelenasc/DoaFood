<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_doador         = $_SESSION['usuario_id'];
$tipo_alimento     = $_POST['tipo_alimento'] ?? null;
$descricao         = $_POST['descricao'] ?? null;
$quantidade        = $_POST['quantidade'] ?? null;
$validade          = $_POST['validade'] ?? null;
$endereco_retirada = $_POST['endereco_retirada'] ?? null;
$horario           = $_POST['horario'] ?? null;
$status            = $_POST['status'] ?? "disponivel"; // valor padrão


if (!$tipo_alimento || !$descricao || !$quantidade) {
    header("Location: doar.php?erro=campos");
    exit;
}


try {

    $sql = "INSERT INTO doacoes (
                id_doador,
                tipo_alimento,
                descricao,
                quantidade,
                validade,
                endereco_retirada,
                horario,
                status
            ) VALUES (
                :id_doador,
                :tipo_alimento,
                :descricao,
                :quantidade,
                :validade,
                :endereco_retirada,
                :horario,
                :status
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_doador'         => $id_doador,
        ':tipo_alimento'     => $tipo_alimento,
        ':descricao'         => $descricao,
        ':quantidade'        => $quantidade,
        ':validade'          => $validade,
        ':endereco_retirada' => $endereco_retirada,
        ':horario'           => $horario,
        ':status'            => $status
    ]);

    header("Location: listar_doacoes.php?sucesso=1");
    exit;

} catch (PDOException $e) {

   
    error_log("ERRO AO DOAR: " . $e->getMessage());
    header("Location: doar.php?erro=db");
    exit;
}

?>
