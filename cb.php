<?php
 
$host = "localhost";
$user = "root";
$pass = "&tec77@info!";
$banco = "cafeteria";
 
$conexao = mysqli_connect($host, $user, $pass, $banco);
if(mysqli_connect_error()){
    die('não foi possivel conectar:' .mysqli_connect_error());
}
 
 
?>