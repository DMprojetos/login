<?php
// Configurações do banco de dados
$servername = "srv1595.hstgr.io"; // Endereço do servidor MySQL
$username = "u870367221_Barber";  // Nome de usuário do banco de dados
$password = "Deividlps120@";     // Senha do banco de dados
$dbname = "u870367221_Barber";       // Nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário e sanitizá-los
$nome = $conn->real_escape_string($_POST['nome']);
$email = $conn->real_escape_string($_POST['email']);
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Verificar se as senhas coincidem
if ($senha !== $confirmar_senha) {
    echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
    exit;
}

// Verificar se o email já está cadastrado
$email_verificado = $conn->query("SELECT * FROM usuarios WHERE email='$email'");

if ($email_verificado->num_rows > 0) {
    die("Este email já está cadastrado!");
}

// **ARMAZENANDO A SENHA EM TEXTO CLARO** - Cuidado com isso!
$senha_armazenada = $senha; // Armazenando a senha sem criptografia

// Preparar a consulta SQL
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha_armazenada);

// Executar a consulta
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
