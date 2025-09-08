<?php
session_start();
require_once(__DIR__ . "/../../cb.php"); 


$email = $_GET['email'] ?? '';
$senha = $_GET['password'] ?? '';


$sql = "SELECT * FROM cliente WHERE email = '$email' AND senha = '$senha'";
$result = mysqli_query($conexao, $sql);


if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    $_SESSION['idcliente'] = $user['idcliente'];
    $_SESSION['nome'] = $user['nome'];

    echo"<script>
        alert('Logado com sucesso');
        window.location.href = '../../fachada/fachada.html';
    </script>";
die(); 
} 

else {
   echo"<script>
        alert('Email ou Senha incorretos 🤡🤡🤡');
        window.location.href = '../login.html';
    </script>";
}
?>