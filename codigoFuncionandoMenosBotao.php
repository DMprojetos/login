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

        .btn:disabled {
    background-color: #d3d3d3; /* Cor para botões desabilitados */
    color: #a9a9a9; /* Texto cinza claro */
    cursor: not-allowed; /* Cursor como proibido */
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
        .btn:hover {
    background-color: var(--time-hover-color);
    transition: background-color 0.3s;
}

.day:hover {
    background-color: var(--time-hover-color);
}

.button-container button:hover {
    background-color: #007bff; /* Cor primária do Bootstrap */
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
                    showProfessionals(); // Carrega os profissionais ao escolher a data
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
        // Aqui você pode exibir os profissionais disponíveis, ex: adicionando botões para cada profissional
        document.getElementById('baiano-btn').style.display = 'block'; // Exibir botões de profissionais
        document.getElementById('gabriel-btn').style.display = 'block';
    }

    document.getElementById('baiano-btn').addEventListener('click', function () {
        fetchHours('Baiano');
    });

    document.getElementById('gabriel-btn').addEventListener('click', function () {
        fetchHours('Gabriel');
    });

    function fetchHours(professional, selectedDate) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "verificar_agendamento.php", true); // Certifique-se de que a URL está correta
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const unavailableHours = JSON.parse(xhr.responseText);
            generateHourButtons(professional, unavailableHours);
        }
    };
    xhr.send("professional=" + encodeURIComponent(professional) + "&date=" + encodeURIComponent(selectedDate)); // Envia o dia selecionado
}

function generateHourButtons(person, unavailableHours) {
    const hourButtonsContainer = document.getElementById('hour-buttons');
    hourButtonsContainer.innerHTML = '';

    // Array com os intervalos de horas comerciais do dia
    const hours = [
        '08:00', '09:00', '10:00', '11:00',
        '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
    ];

    // Cria os botões de horário
    hours.forEach(hour => {
        const divCol = document.createElement('div');
        divCol.className = 'col-4';

        const button = document.createElement('button');
        button.className = 'btn w-100';
        button.textContent = `${hour} - ${person}`;

        // Desabilitar botão se o horário estiver ocupado
        if (unavailableHours.includes(hour)) {
            button.classList.add('btn-danger');
            button.disabled = true; // Desabilita o botão
        } else {
            button.classList.add('btn-outline-info');

            // Adiciona o evento de clique para finalizar o agendamento
            button.addEventListener('click', function() {
                // Define os valores dos campos antes de finalizar o agendamento
                document.getElementById('inputNome').value = person; // Nome do profissional
                document.getElementById('inputDia').value = document.getElementById('inputDia').value; // Data selecionada
                document.getElementById('inputProfissional').value = person; // Profissional selecionado
                document.getElementById('inputHorario').value = hour; // Horário selecionado

                // Chama a função de finalização do agendamento
                finalizeAppointment();
            });
        }

        divCol.appendChild(button);
        hourButtonsContainer.appendChild(divCol);
    });
}

function finalizeAppointment() {
    const nome = document.getElementById('inputNome').value;
    const dia = document.getElementById('inputDia').value;
    const profissional = document.getElementById('inputProfissional').value;
    const horario = document.getElementById('inputHorario').value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "config.php", true); // Apontando para o arquivo PHP para finalização
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Agendamento realizado com sucesso!");
            // Aqui você pode adicionar lógica para limpar os campos ou ocultar o container
            document.getElementById('inputNome').value = ''; // Limpa o campo nome
            document.getElementById('inputDia').value = ''; // Limpa o campo dia
            document.getElementById('inputProfissional').value = ''; // Limpa o campo profissional
            document.getElementById('inputHorario').value = ''; // Limpa o campo horário
            document.getElementById('hour-buttons').innerHTML = ''; // Limpa os botões de horário
        } else {
            alert("Ocorreu um erro ao agendar. Tente novamente.");
        }
    };
    xhr.send(`nome=${encodeURIComponent(nome)}&dia=${encodeURIComponent(dia)}&profissional=${encodeURIComponent(profissional)}&horario=${encodeURIComponent(horario)}`);
}

</script>

</body>

</html>
