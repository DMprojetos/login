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

    // Recebe o profissional e a data via POST
    $professional = $_POST['professional'];
    $date = $_POST['date'];  // Esperado no formato YYYY-MM-DD

    // Consulta os horários ocupados para o profissional
    $sql = "SELECT horario FROM agendamentos WHERE profissional = ? AND dia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $professional, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $unavailableHours = []; // Array para armazenar os horários indisponíveis
    while ($row = $result->fetch_assoc()) {
        $unavailableHours[] = $row['horario']; // Adiciona apenas o horário
    }

    echo json_encode($unavailableHours); // Retorna apenas os horários

    $stmt->close();
    $conn->close();
    exit;
}
?>
