<?php
session_start();

// Verifica se está logado e se é funcionário
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != "funcionario"){
    header("Location: ../login.html");
    exit;
}

include '../cb.php'; // Conexão com o banco

// Mensagens
$mensagem = "";

// Cadastro de funcionário
if(isset($_POST['cadastrar'])){
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $telefone = $conexao->real_escape_string($_POST['telefone']);
    $genero = $conexao->real_escape_string($_POST['genero']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO funcionarios (nome_func, email_func, senha_func, telefone, genero_fuc) 
            VALUES ('$nome', '$email', '$senha', '$telefone', '$genero')";
    if($conexao->query($sql)){
        $mensagem = "Funcionário cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar: " . $conexao->error;
    }
}

// Exclusão de funcionário
if(isset($_GET['excluir'])){
    $idfuncionarios = intval($_GET['excluir']);
    $sql = "DELETE FROM funcionarios WHERE idfuncionarios = $idfuncionarios";
    $conexao->query($sql);
    header("Location: funcionarios_crud.php");
    exit;
}

// Edição de funcionário
if(isset($_POST['editar'])){
    $idfuncionarios = intval($_POST['id']);
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $telefone = $conexao->real_escape_string($_POST['telefone']);
    $genero = $conexao->real_escape_string($_POST['genero']);
    $senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

    if($senha){
        $sql = "UPDATE funcionarios 
                SET nome_func='$nome', email_func='$email', senha_func='$senha', telefone='$telefone', genero_fuc='$genero' 
                WHERE idfuncionarios=$idfuncionarios";
    } else {
        $sql = "UPDATE funcionarios 
                SET nome_func='$nome', email_func='$email', telefone='$telefone', genero_fuc='$genero' 
                WHERE idfuncionarios=$idfuncionarios";
    }

    $conexao->query($sql);
    header("Location: func_crud.php");
    exit;
}

// Busca funcionários
$sql = "SELECT * FROM funcionarios ORDER BY idfuncionarios DESC";
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Funcionários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/dash.css">
</head>
<body>

<style>
   
</style>

<header>
    <div class="header-top">
        <a href="dashboard.php" class="btn-voltar">← Voltar</a>
        <h1>Gestão de Funcionários</h1>
        <p>Bem-vindo, <?php echo $_SESSION['nome']; ?> | <a href="../logout.php">Sair</a></p>
    </div>
</header>

<main>
    <?php if($mensagem) echo "<p style='text-align:center; color:green; font-weight:bold;'>$mensagem</p>"; ?>

    <!-- Formulário de cadastro -->
    <div class="card">
        <h2>Cadastrar Funcionário</h2>
        <form method="POST" style="display:flex; flex-direction:column; gap:10px;">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telefone" placeholder="Telefone">
            <input type="text" name="genero" placeholder="Gênero">
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="cadastrar"><i class="fa-solid fa-plus"></i> Cadastrar</button>
        </form>
    </div>

    <!-- Listagem de funcionários -->
    <?php while($func = $result->fetch_assoc()): ?>
    <div class="card">
        <h2><?php echo $func['nome_func']; ?></h2>
        <p>Email: <?php echo $func['email_func']; ?></p>
        <p>Telefone: <?php echo $func['telefone']; ?></p>
        <p>Gênero: <?php echo $func['genero_fuc']; ?></p>
        <div style="display:flex; justify-content:center; gap:10px;">
            <button onclick="editar(
                <?php echo $func['idfuncionarios']; ?>,
                '<?php echo $func['nome_func']; ?>',
                '<?php echo $func['email_func']; ?>',
                '<?php echo $func['telefone']; ?>',
                '<?php echo $func['genero_fuc']; ?>'
            )">
                <i class="fa-solid fa-pen-to-square"></i> Editar
            </button>
            <button onclick="if(confirm('Deseja realmente excluir?')) location.href='func_crud.php?excluir=<?php echo $func['idfuncionarios']; ?>'">
                <i class="fa-solid fa-trash"></i> Excluir
            </button>
        </div>
    </div>
    <?php endwhile; ?>
</main>

<!-- Modal simples para edição -->
<div id="modal-editar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div class="card" style="width:400px;">
        <h2>Editar Funcionário</h2>
        <form method="POST" style="display:flex; flex-direction:column; gap:10px;">
            <input type="hidden" name="id" id="edit-id">
            <input type="text" name="nome" id="edit-nome" placeholder="Nome" required>
            <input type="email" name="email" id="edit-email" placeholder="Email" required>
            <input type="text" name="telefone" id="edit-telefone" placeholder="Telefone">
            <input type="text" name="genero" id="edit-genero" placeholder="Gênero">
            <input type="password" name="senha" placeholder="Nova Senha (opcional)">
            <button type="submit" name="editar"><i class="fa-solid fa-pen"></i> Salvar</button>
            <button type="button" onclick="fecharModal()" style="background:red;"><i class="fa-solid fa-xmark"></i> Cancelar</button>
        </form>
    </div>
</div>

<script>
function editar(id, nome, email, telefone, genero){
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nome').value = nome;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-telefone').value = telefone;
    document.getElementById('edit-genero').value = genero;
    document.getElementById('modal-editar').style.display = 'flex';
}

function fecharModal(){
    document.getElementById('modal-editar').style.display = 'none';
}
</script>

</body>
</html>
