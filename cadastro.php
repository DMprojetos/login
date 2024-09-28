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

// Receber os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Verificar se as senhas coincidem
if ($senha !== $confirmar_senha) {
    die("As senhas não coincidem!");
}

// Criptografar a senha
$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

// Inserir os dados no banco de dados
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_criptografada')";

if ($conn->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Fechar a conexão
$conn->close();
?>
