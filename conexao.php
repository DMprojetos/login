<?php
$servername = "srv1595.hstgr.io"; // Endereço do servidor MySQL
$username = "u870367221_Barber";   // Nome de usuário do banco de dados
$password = "Deividlps120@";        // Senha do banco de dados
$dbname = "u870367221_Barber";      // Nome do banco de dados

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
