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
    $profissional = isset($_POST['Profissional']) ? $_POST['Profissional'] : '';

    // Verifica se os campos estão vazios
    if (empty($nome) || empty($dia) || empty($horario) || empty($profissional)) {
        echo "Todos os campos são obrigatórios!";
        exit;
    }

    // Prepara a instrução SQL
    $stmt = $conn->prepare("INSERT INTO agendamentos (nome, dia, horario, profissional) VALUES (?, ?, ?, ?)");
    $profissional = isset($_POST['Profissional']) ? $_POST['Profissional'] : '';

    // Executa a inserção e verifica
    if ($stmt->execute()) {
        echo "Agendamento realizado com sucesso!";
    } else {
        echo "Erro ao agendar: " . $stmt->error;
    }

    // Fecha a declaração e a conexão
    $stmt->close();
}

$conn->close();
?>
