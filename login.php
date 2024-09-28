<?php
session_start();

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifique as credenciais (normalmente, você faria isso consultando um banco de dados)
    $userEmail = 'usuario@exemplo.com';
    $userPassword = 'senha123'; // A senha geralmente seria armazenada de forma criptografada

    // Simulação de verificação de login
    if ($email == $userEmail && $password == $userPassword) {
        // Credenciais válidas, cria a sessão
        $_SESSION['usuario'] = $email;
        
        // Redireciona para a página inicial após login
        header('Location: home.php');
        exit;
    } else {
        // Mensagem de erro em caso de login inválido
        echo "Email ou senha inválidos!";
    }
}
?>
