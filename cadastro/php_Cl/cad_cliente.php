<?php 
session_start();
require_once(__DIR__ . "/../../cb.php");

$nome  = $_POST['name'];
$senha = $_POST['password'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$verifica = "SELECT * FROM cliente WHERE email='$email'";
$result_verifica = mysqli_query($conexao, $verifica);

if ($result_verifica && mysqli_num_rows($result_verifica) > 0) {
    echo "<script>
            alert('Email já cadastrado 🤡');
            window.history.back();
          </script>";
    exit();
}


$query = "INSERT INTO cliente (nome, senha, email, telefone) VALUES ('$nome', '$senha', '$email','$telefone')";

$gravar = mysqli_query($conexao, $query);

if ($gravar) {
    echo "<script>
        alert('Dados gravados com sucesso');
        window.location.href = '../login.html';
    </script>";
} else {
    echo "Erro: " . mysqli_error($conexao);
}
?>