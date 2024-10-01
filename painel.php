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

// Consulta os agendamentos
$sql = "SELECT nome, dia, horario, profissional FROM agendamentos";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Agendamentos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Painel de Agendamentos</h1>
    <table>
        <tr>
            <th>Nome</th>
            <th>Dia</th>
            <th>Horário</th>
            <th>Profissional</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Saída de cada linha da tabela
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dia']) . "</td>";
                echo "<td>" . htmlspecialchars($row['horario']) . "</td>";
                echo "<td>" . htmlspecialchars($row['profissional']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum agendamento encontrado</td></tr>";
        }
        ?>
    </table>

    <?php
    // Fecha a conexão
    $conn->close();
    ?>
</body>
</html>
