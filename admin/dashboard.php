<?php
session_start();

// Verifica se está logado e se é funcionário
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != "funcionario"){
    header("Location: ../cadastro/login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/func.css">
</head>
<body>
    <header>
        <div class="header-top">
            <h1>Painel Administrativo</h1>
            <p>Bem-vindo, <?php echo $_SESSION['nome']; ?> | 
               <a href="../logout.php">Sair</a>
            </p>
        </div>
    </header>


    <main>
    <div class="card">
        <h2>Funcionários</h2>
        <p>Cadastrar, editar e remover funcionários.</p>
        <button onclick="location.href='func_crud.php'">Acessar</button>
    </div>

    <div class="card">
        <h2>Pedidos</h2>
        <p>Acompanhar pedidos dos clientes.</p>
        <button onclick="location.href='ped_crud.php'">Acessar</button>
    </div>

    <div class="card">
        <h2>Fornecedores</h2>
        <p>Cadastrar e editar fornecedores.</p>
        <button onclick="location.href='fornecedores_crud.php'">Acessar</button>
    </div>
</main>

</body>
</html>
