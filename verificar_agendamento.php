<?php
$servername = "srv1595.hstgr.io";
$username = "u870367221_Barber";
$password = "Deividlps120@";
$dbname = "u870367221_Barber";

// Checa se é uma requisição AJAX para buscar horários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checagem da conexão
    if ($conn->connect_error) {
        die(json_encode(["error" => "Falha na conexão: " . $conn->connect_error]));
    }

    // Recebe o profissional via POST
    $professional = $_POST['professional'];
    $day = $_POST['dia'];  // Recebendo o dia via POST


    // Consulta os horários ocupados para o profissional
    $sql = "SELECT profissional, horario, dia FROM agendamentos WHERE profissional = ? AND dia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $professional, $day);
    $stmt->execute();
    $result = $stmt->get_result();

    $unavailableAppointments = [];
    while ($row = $result->fetch_assoc()) {
        // Adiciona um objeto com profissional, horário e dia ao array
        $unavailableAppointments[] = [
            'profissional' => $row['profissional'],
            'horario' => $row['horario'],
            'dia' => $row['dia']
        ];
    }

    // Retorna os horários ocupados em formato JSON
    echo json_encode($unavailableAppointments);

    $stmt->close();
    $conn->close();
    exit;
}
?>