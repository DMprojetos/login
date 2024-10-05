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
    $sql = "SELECT horario FROM agendamentos WHERE profissional = ?";
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
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --bg-color: #fff;
            --day-bg-color: #2D2D2D;
            --active-day-color: #FEC810;
            --time-bg-color: #2D2D2D;
            --time-hover-color: #FEC810;
            --confirm-bg-color: #FEC810;
            --confirm-hover-color: #E0B000;
            --text-color: #ffffff;
            --info-bg-color: #2D2D2D;
            --input-bg-color: #ffffff;
            --input-border-color: #cccccc;
            --input-focus-border-color: #FEC810;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: var(--text-color);
            width: 100%;
            max-width: 430px;
            margin: auto;
            background: url("https://i.pinimg.com/564x/60/a1/a7/60a1a7c506764c9b9456cef28bc896d3.jpg") no-repeat center center fixed;
            background-size: cover;
        }

        h1,
        h2 {
            font-size: 36px;
            text-align: center;
            color: rgb(255, 255, 255);
        }

        .container {
            width: 90%;
            max-width: 400px;
            margin: 10px auto;
            text-align: center;
            background: transparent;
            border: 2px solid #7e7e7e;
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }

        .days-selection {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .day {
            background-color: var(--day-bg-color);
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: var(--text-color);
        }

        .day.active {
            background-color: var(--active-day-color);
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            text-align: center;
        }

        .hour-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .col-4 {
            flex: 1 0 30%;
            margin: 5px;
        }

        .btn {
            background-color: var(--day-bg-color);
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
            color: var(--text-color);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-outline-info {
            background-color: #0dcaf0;
        }

        .hidden {
            display: none;
        }

        .info {
            font-size: 14px;
            background-color: var(--info-bg-color);
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--input-border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            background-color: var(--input-bg-color);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--input-focus-border-color);
        }

        @media (max-width: 400px) {
            .day {
                width: calc(50% - 4px);
            }

            .container {
                padding: 15px;
            }

            h2 {
                font-size: 12px;
            }

            .info {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <form action="config.php" method="POST" id="agendamentoForm">
            <h2 class="info">Nome</h2>
            <input type="text" name="nome" id="inputNome" placeholder="Digite seu nome" required>
            <button type="button" id="prosseguir-btn" class="btn btn-primary mt-2">Prosseguir</button>

            <div id="days-container" class="hidden">
                <h1 class="info">Agendamento de Horários</h1>
                <div class="days-selection" id="daysSelection"></div>
            </div>

            <input type="hidden" name="dia" id="inputDia" value="">
            <input type="hidden" name="horario" id="inputHorario" value="09:05">
            <input type="hidden" name="Profissional" id="inputProfissional" value="">

            <div id="professionals-container" class="hidden">
                <h1>Escolha o Profissional</h1>
                <div class="button-container">
                    <button type="button" id="baiano-btn" class="btn btn-primary">Baiano</button>
                    <button type="button" id="gabriel-btn" class="btn btn-primary">Gabriel</button>
                </div>
                <div id="hour-buttons" class="hour-buttons mt-3"></div>
            </div>
        </form>
    </div>

    <script>
        function formatDate(date) {
            const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const weekday = weekdays[date.getDay()];
            return `${day}/${month} - ${weekday}`;
        }

        const daysSelection = document.getElementById('daysSelection');
        const today = new Date();

        document.getElementById('prosseguir-btn').addEventListener('click', function () {
            const inputNome = document.getElementById('inputNome').value;
            if (inputNome) {
                document.getElementById('days-container').classList.remove('hidden');
                daysSelection.innerHTML = '';

                for (let i = 0; i < 7; i++) {
                    const nextDate = new Date(today);
                    nextDate.setDate(today.getDate() + i);
                    if (nextDate.getDay() === 0) continue; // Ignora o domingo
                    const formattedDate = formatDate(nextDate);

                    const dayButton = document.createElement('button');
                    dayButton.className = 'btn btn-secondary day mx-1';
                    dayButton.innerHTML = formattedDate;
                    dayButton.setAttribute('data-date', nextDate.toISOString().split('T')[0]);

                    dayButton.addEventListener('click', function () {
                        document.querySelectorAll('.day').forEach(d => d.classList.remove('active'));
                        this.classList.add('active');
                        document.getElementById('inputDia').value = this.getAttribute('data-date');
                        // Ao selecionar a data, também exibe os profissionais
                        showProfessionals();
                    });

                    daysSelection.appendChild(dayButton);
                }
            } else {
                alert("Por favor, digite seu nome.");
            }
        });

        function showProfessionals() {
            document.getElementById('professionals-container').classList.remove('hidden');
            const selectedDate = document.getElementById('inputDia').value;
            console.log("Data selecionada: ", selectedDate);
            // Aqui, você pode buscar os horários para a data selecionada ou simplesmente exibir os profissionais
            // Para fins de exemplo, vamos chamar a função diretamente
        }

        document.getElementById('baiano-btn').addEventListener('click', function () {
            fetchHours('Baiano');
        });

        document.getElementById('gabriel-btn').addEventListener('click', function () {
            fetchHours('Gabriel');
        });

        function fetchHours(professional) {
            const dia = document.getElementById('inputDia').value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const unavailableHours = JSON.parse(xhr.responseText);
                    generateHourButtons(unavailableHours);
                    document.getElementById('inputProfissional').value = professional;
                }
            };
            xhr.send(`action=getHours&nome=${document.getElementById('inputNome').value}&profissional=${professional}&dia=${dia}`);
        }

        function generateHourButtons(unavailableHours) {
            const hourButtonsContainer = document.getElementById('hour-buttons');
            hourButtonsContainer.innerHTML = ''; // Limpa os botões de horas existentes
            const allHours = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];

            allHours.forEach(function (hour) {
                const hourButton = document.createElement('button');
                hourButton.className = 'btn btn-primary col-4';
                hourButton.innerHTML = hour;

                if (unavailableHours.includes(hour)) {
                    hourButton.classList.add('btn-danger');
                    hourButton.disabled = true; // Desativa botões de horários indisponíveis
                } else {
                    hourButton.addEventListener('click', function () {
                        document.getElementById('inputHorario').value = hour;
                        hourButtonsContainer.querySelectorAll('.btn').forEach(btn => btn.classList.remove('btn-outline-info'));
                        this.classList.add('btn-outline-info');
                    });
                }
                hourButtonsContainer.appendChild(hourButton);
            });
        }
    </script>
</body>

</html>
