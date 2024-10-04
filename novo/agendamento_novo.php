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

    // Consulta os horários ocupados para o profissional
    $sql = "SELECT horario FROM dados WHERE profissional = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $professional);
    $stmt->execute();
    $result = $stmt->get_result();

    $unavailableHours = [];
    while ($row = $result->fetch_assoc()) {
        $unavailableHours[] = $row['horario'];
    }

    // Retorna os horários ocupados em formato JSON
    echo json_encode($unavailableHours);

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Horários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .hour-buttons {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

