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

// Verifica se a exclusão foi solicitada
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM agendamentos WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        // Redireciona para a página principal após a exclusão
        header("Location: painel.php"); // Substitua 'config.php' pelo nome correto do arquivo
        exit(); // Encerra o script após o redirecionamento
    } else {
        echo "<script>alert('Erro ao excluir o agendamento.');</script>";
    }
    
    $stmt->close();
}

// Consulta os agendamentos
$sql = "SELECT id, nome, dia, horario, profissional FROM agendamentos";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Agendamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            color: #555;
        }

        @media (max-width: 600px) {
            table {
                width: 100%;
            }

            th, td {
                padding: 10px;
            }

            h1 {
                font-size: 24px;
            }
        }

        .delete-button {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-button:hover {
            text-decoration: underline;
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
            <th>Ação</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Saída de cada linha da tabela
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td>" . date("d/m/Y", strtotime($row['dia'])) . "</td>"; // Formato DD/MM/YYYY
                echo "<td>" . htmlspecialchars($row['horario']) . "</td>";
                echo "<td>" . htmlspecialchars($row['profissional']) . "</td>";
                echo "<td><a href='?delete_id=" . $row['id'] . "' class='delete-button' onclick='return confirm(\"Tem certeza que deseja excluir este agendamento?\")'>Excluir</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum agendamento encontrado</td></tr>";
        }
        ?> 
    </table>

    <?php
    // Fecha a conexão
    $conn->close();
    ?>
</body>
</html>
