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

// Verifica se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = isset($_POST['nome']) ? $_POST['nome'] : ''; // Capturando o nome
    $dia = isset($_POST['dia']) ? $_POST['dia'] : '';
    $horario = isset($_POST['horario']) ? $_POST['horario'] : '';
    $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : ''; // Capturando o periodo

    // Sanitiza os dados
    $nome = $conn->real_escape_string($nome);
    $dia = $conn->real_escape_string($dia);
    $horario = $conn->real_escape_string($horario);
    $periodo = $conn->real_escape_string($periodo);

    // Prepara a consulta SQL para inserir os dados
    $stmt = $conn->prepare("INSERT INTO agendamentos (nome, dia, periodo, horario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $dia, $periodo, $horario); // "ssss" indica que todas as variáveis são strings

    // Executa a consulta
    if ($stmt->execute()) {
        echo "<div class='message'>Agendamento realizado com sucesso!</div>";
    } else {
        echo "<div class='message'>Erro: " . $stmt->error . "</div>";
    }

    // Fecha a declaração
    $stmt->close();
}

// Fecha a conexão
$conn->close();
?>
