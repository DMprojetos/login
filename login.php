<?php
// Configurações do banco de dados
$servername = "srv1595.hstgr.io"; // Endereço do servidor MySQL
$username = "u870367221_Barber";  // Nome de usuário do banco de dados
$password = "Deividlps120@";       // Senha do banco de dados
$dbname = "u870367221_Barber";     // Nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário e sanitizá-los
$email = $conn->real_escape_string(trim($_POST['email'])); // Sanitiza a entrada do email
$senha = $_POST['senha']; // Obtém a entrada da senha

// Preparar a consulta SQL para verificar o usuário
$stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email); // Liga o parâmetro do email
$stmt->execute(); // Executa a consulta
$stmt->store_result(); // Armazena o resultado para verificações futuras

// Verificar se o usuário existe
if ($stmt->num_rows > 0) {
    // O usuário existe, agora recuperar a senha armazenada
    $stmt->bind_result($senha_armazenada); // Liga o resultado à variável
    $stmt->fetch(); // Obtém o resultado

    // Verificar a senha
    if ($senha === $senha_armazenada) {
        // Senha correta
        session_start(); // Inicia a sessão
        $_SESSION['usuarios'] = $email; // Armazena o email do usuário na sessão
        header("Location: dashboard.php"); // Redireciona para uma página protegida
        exit();
    } else {
        // Senha incorreta
        echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        exit();
    }
} else {
    // Usuário não encontrado
    echo "<script>alert('Este email não está cadastrado!'); window.history.back();</script>";
    exit();
}

// Fechar a conexão
$stmt->close(); // Fecha a declaração
$conn->close(); // Fecha a conexão
?>
