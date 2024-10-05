<?php
// fetch_hours.php

$servername = "srv1595.hstgr.io";
$username = "u870367221_Barber";
$password = "Deividlps120@";
$dbname = "u870367221_Barber";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checagem da conexão
    if ($conn->connect_error) {
        die(json_encode(["error" => "Falha na conexão: " . $conn->connect_error]));
    }

    // Recebe os dados via POST
    $professional = $_POST['profissional'] ?? null; // Uso do operador null coalescing
    $day = $_POST['dia'] ?? null;

    if ($professional && $day) {
        // Consulta os horários ocupados
        $sql = "SELECT horario FROM agendamentos WHERE profissional = ? AND dia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $professional, $day);
        $stmt->execute();
        $result = $stmt->get_result();

        $unavailableAppointments = [];
        while ($row = $result->fetch_assoc()) {
            $unavailableAppointments[] = ['horario' => $row['horario']]; // Retornar como um objeto
        }

        echo json_encode($unavailableAppointments);
    } else {
        echo json_encode(["error" => "Dados insuficientes."]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
