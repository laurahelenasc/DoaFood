<?php
session_start();
require_once '../app/config/db.php';

//--------------------------------------------------------
// 1) Segurança: exige login e método POST
//--------------------------------------------------------
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: listar_doacoes.php?erro=bad_method");
    exit;
}

if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: listar_doacoes.php?erro=invalid_id");
    exit;
}

$id = intval($_POST['id']);
$id_doador = intval($_SESSION['usuario_id']);

//--------------------------------------------------------
// 2) Verifica se a doação pertence ao usuário
//--------------------------------------------------------
$sqlCheck = "SELECT id 
             FROM doacoes 
             WHERE id = :id 
             AND id_doador = :id_doador";

$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([
    ':id' => $id,
    ':id_doador' => $id_doador
]);

$doacao = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$doacao) {
    header("Location: listar_doacoes.php?erro=permissao");
    exit;
}

//--------------------------------------------------------
// 3) Exclusão "manual cascade" (pedidos + doação)
//--------------------------------------------------------
try {
    $pdo->beginTransaction();

    // Exclui pedidos associados
    $sql1 = "DELETE FROM pedidos WHERE doacao_id = :id";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([':id' => $id]);

    // Exclui a doação
    $sql2 = "DELETE FROM doacoes WHERE id = :id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':id' => $id]);

    $pdo->commit();

    header("Location: listar_doacoes.php?sucesso=delete");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: listar_doacoes.php?erro=db&msg=" . urlencode($e->getMessage()));
    exit;
}
