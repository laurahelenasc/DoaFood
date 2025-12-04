<?php
session_start();
require_once '../app/config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_POST['id'];
$id_doador = $_SESSION['usuario_id'];

$tipo_alimento = $_POST['tipo_alimento'];
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];
$validade = $_POST['validade'];
$endereco_retirada = $_POST['endereco_retirada'];
$horario = $_POST['horario'];
$status = $_POST['status'];

// Atualiza somente se a doação pertence ao usuário
$sql = "UPDATE doacoes
        SET tipo_alimento = :tipo_alimento,
            descricao = :descricao,
            quantidade = :quantidade,
            validade = :validade,
            endereco_retirada = :endereco_retirada,
            horario = :horario,
            status = :status
        WHERE id = :id AND id_doador = :id_doador";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':tipo_alimento' => $tipo_alimento,
    ':descricao' => $descricao,
    ':quantidade' => $quantidade,
    ':validade' => $validade,
    ':endereco_retirada' => $endereco_retirada,
    ':horario' => $horario,
    ':status' => $status,
    ':id' => $id,
    ':id_doador' => $id_doador
]);

header("Location: listar_doacoes.php?editado=1");
exit;
