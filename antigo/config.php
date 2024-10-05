<?php
// config.php

// Configurações do banco de dados
$servername = "srv1595.hstgr.io"; // Endereço do servidor MySQL
$username = "u870367221_Barber";  // Nome de usuário do banco de dados
$password = "Deividlps120@";       // Senha do banco de dados
$dbname = "u870367221_Barber";     // Nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recebe os dados do POST
$nome = $_POST['nome'] ?? '';
$dia = $_POST['dia'] ?? '';
$profissional = $_POST['profissional'] ?? '';
$horario = $_POST['horario'] ?? '';

// Valida os dados
if ($nome && $dia && $profissional && $horario) {
    // Prepara e vincula
    $stmt = $conn->prepare("INSERT INTO agendamentos (nome, dia, profissional, horario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $dia, $profissional, $horario);

    // Tenta executar
    if ($stmt->execute()) {
        echo "Agendamento realizado com sucesso!";
    } else {
        echo "Erro ao agendar: " . $stmt->error;
    }

    // Fecha a declaração
    $stmt->close();
} else {
    echo "Todos os campos são obrigatórios.";
}

// Fecha a conexão
$conn->close();
?>
