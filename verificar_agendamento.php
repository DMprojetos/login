<?php
// verificar_agendamento.php

// Configurações do banco de dados
$servername = "srv1595.hstgr.io";
$username = "u870367221_Barber";
$password = "Deividlps120@";
$dbname = "u870367221_Barber";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'message' => $e->getMessage()]);
    exit();
}

// Recebendo os dados do POST
$dia = isset($_POST['dia']) ? $_POST['dia'] : '';
$horario = isset($_POST['horario']) ? $_POST['horario'] : '';

// Validar os dados
if (empty($dia) || empty($horario)) {
    echo json_encode(['status' => 'erro', 'message' => 'Dados inválidos']);
    exit();
}

// Verificar se o horário está ocupado
$query = "SELECT COUNT(*) FROM agendamentos WHERE dia = :dia AND horario = :horario";
$stmt = $conn->prepare($query);
$stmt->bindParam(':dia', $dia);
$stmt->bindParam(':horario', $horario);
$stmt->execute();

$resultado = $stmt->fetchColumn();

if ($resultado > 0) {
    echo json_encode(['status' => 'ocupado']);
} else {
    echo json_encode(['status' => 'disponivel']);
}
?>