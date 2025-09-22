<?php
session_start();
require_once(__DIR__ . "/../../cb.php"); 

$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

// 1. Tenta login como FUNCIONÁRIO/ADMIN
$sql_func = "SELECT * FROM funcionarios WHERE email_func = '$email' AND senha_func = '$senha'";
$result_func = mysqli_query($conexao, $sql_func);

if ($result_func && mysqli_num_rows($result_func) > 0) {
    $func = mysqli_fetch_assoc($result_func);

    $_SESSION['idfuncionario'] = $func['idfuncionarios'];
    $_SESSION['nome'] = $func['nome_func'];
    $_SESSION['tipo'] = "funcionario"; // ou "funcionario" se quiser diferenciar

    echo "<script>
        alert('Login de Administrador realizado com sucesso!');
        window.location.href = '../../admin/dashboard.php'; // Página do admin
    </script>";
    die();
}

// 2. Tenta login como CLIENTE COMUM
$sql_cli = "SELECT * FROM cliente WHERE email = '$email' AND senha = '$senha'";
$result_cli = mysqli_query($conexao, $sql_cli);

if ($result_cli && mysqli_num_rows($result_cli) > 0) {
    $user = mysqli_fetch_assoc($result_cli);

    $_SESSION['idcliente'] = $user['idcliente'];
    $_SESSION['nome'] = $user['nome'];
    $_SESSION['tipo'] = "cliente";

    echo "<script>
        alert('Login de Cliente realizado com sucesso!');
        window.location.href = '../../fachada/fachada.html'; // Página do cliente
    </script>";
    die();
}

// Se não encontrou em nenhuma tabela
echo "<script>
    alert('Email ou Senha incorretos. Tente novamente!');
    window.location.href = '../login.html';
</script>";
?>