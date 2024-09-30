<?php
// Configurações do banco de dados
$servername = "srv1595.hstgr.io"; // Endereço do servidor MySQL
$username = "u870367221_Barber";  // Nome de usuário do banco de dados
$password = "Deividlps120@";      // Senha do banco de dados
$dbname = "u870367221_Barber";    // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se há erros na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Receber os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Sanitizar os dados (para evitar SQL injection)
$email = $conn->real_escape_string($email);

// Verificar se o usuário existe no banco de dados
$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // O email foi encontrado
    $user = $result->fetch_assoc();
    
    // Verificar se a senha está correta
   if ($user['senha'] === $senha) {
    // Senha correta, o login é bem-sucedido
    echo "Login realizado com sucesso! Bem-vindo, " . $user['nome'] . ".";

    // Redirecionar para a página de agendamento
    header('Location: index.php');
    exit(); // Sempre use exit() após o header para garantir que o script pare aqui
    } else {
    // Senha incorreta
    echo "Senha incorreta!";
}

} else {
    // Email não encontrado
    echo "Email não encontrado!";
}

// Fechar a conexão
$conn->close();
?>
