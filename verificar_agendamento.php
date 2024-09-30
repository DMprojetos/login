<?php
// verificar_agendamento.php

// Configurações do banco de dados
$servername = "srv1595.hstgr.io";
$username = "u870367221_Barber";
$password = "Deividlps120@";
$dbname = "u870367221_Barber";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se os parâmetros de data e horário foram recebidos
if (isset($_POST['dia']) && isset($_POST['horario'])) {
    $dia = $conn->real_escape_string($_POST['dia']);
    $horario = $conn->real_escape_string($_POST['horario']);

    // Prepara a consulta SQL para verificar se o agendamento já existe
    $sql = "SELECT COUNT(*) as total FROM agendamentos WHERE dia = ? AND horario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dia, $horario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verifica se há algum agendamento para o dia e horário informados
    if ($row['total'] > 0) {
        echo json_encode(['status' => 'ocupado']); // Retorna status 'ocupado' se já houver agendamento
    } else {
        echo json_encode(['status' => 'disponivel']); // Retorna 'disponivel' se o horário estiver livre
    }

    // Fecha a consulta
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
