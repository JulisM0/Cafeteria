<?php
session_start();

// Verifica se está logado e se é funcionário
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != "funcionario"){
    header("Location: ../login.html");
    exit;
}

include '../cb.php'; // Conexão com o banco

// Mensagem
$mensagem = "";

// Upload de imagem
function uploadImagem($file){
    $pasta = "uploads/";
    if(!is_dir($pasta)){
        mkdir($pasta, 0777, true);
    }
    $nomeArquivo = time() . "_" . basename($file['name']);
    $caminho = $pasta . $nomeArquivo;
    if(move_uploaded_file($file['tmp_name'], $caminho)){
        return $caminho;
    }
    return "";
}

// Cadastro de produto
if(isset($_POST['cadastrar'])){
    $nome = $conexao->real_escape_string($_POST['nome']);
    $valor = floatval($_POST['valor']);
    $quantidade = intval($_POST['quantidade']);
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $categoria2 = $conexao->real_escape_string($_POST['categoria2']);
    $imagem = isset($_FILES['imagem']) ? uploadImagem($_FILES['imagem']) : "";

    $sql = "INSERT INTO produto (nome, valor, quantidade, categoria, categoria2, imagem) 
            VALUES ('$nome', $valor, $quantidade, '$categoria', '$categoria2', '$imagem')";
    if($conexao->query($sql)){
        $mensagem = "Produto cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar: " . $conexao->error;
    }
}

// Exclusão de produto
if(isset($_GET['excluir'])){
    $idproduto = intval($_GET['excluir']);
    $sql = "DELETE FROM produto WHERE idproduto = $idproduto";
    $conexao->query($sql);
    header("Location: prod_crud.php");
    exit;
}

// Edição de produto
if(isset($_POST['editar'])){
    $idproduto = intval($_POST['id']);
    $nome = $conexao->real_escape_string($_POST['nome']);
    $valor = floatval($_POST['valor']);
    $quantidade = intval($_POST['quantidade']);
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $categoria2 = $conexao->real_escape_string($_POST['categoria2']);

    $imagem = "";
    if(isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0){
        $imagem = uploadImagem($_FILES['imagem']);
        $sql = "UPDATE produtos 
                SET nome='$nome', valor=$valor, quantidade=$quantidade, categoria='$categoria', categoria2='$categoria2', imagem='$imagem' 
                WHERE idproduto=$idproduto";
    } else {
        $sql = "UPDATE produtos 
                SET nome='$nome', valor=$valor, quantidade=$quantidade, categoria='$categoria', categoria2='$categoria2' 
                WHERE idproduto=$idproduto";
    }

    $conexao->query($sql);
    header("Location: prod_crud.php");
    exit;
}

// Busca produtos
$sql = "SELECT * FROM produto ORDER BY idproduto DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRUD Produtos</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="css/dash.css">
</head>
<body>

<header>
    <div class="header-top">
        <a href="dashboard.php" class="btn-voltar">← Voltar</a>
        <h1>Gestão de Produtos</h1>
    </div>
</header>

<main>
    <?php if($mensagem) echo "<p style='text-align:center; color:green; font-weight:bold;'>$mensagem</p>"; ?>

    <!-- Formulário de cadastro -->
    <div class="card">
        <h2>Cadastrar Produto</h2>
        <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:10px;">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="number" step="0.01" name="valor" placeholder="Valor" required>
            <input type="number" name="quantidade" placeholder="Quantidade" required>
            <input type="text" name="categoria" placeholder="Categoria" required>
            <input type="text" name="categoria2" placeholder="Categoria 2">
            <input type="file" name="imagem" accept="image/*">
            <button type="submit" name="cadastrar"><i class="fa-solid fa-plus"></i> Cadastrar</button>
        </form>
    </div>


    <?php while($prod = $result->fetch_assoc()): ?>
    <div class="card">
        <h2><?php echo $prod['nome']; ?></h2>
        <p>Valor: R$ <?php echo number_format($prod['valor'],2,",","."); ?></p>
        <p>Quantidade: <?php echo $prod['quantidade']; ?></p>
        <p>Categoria: <?php echo $prod['categoria']; ?> / <?php echo $prod['categoria2']; ?></p>
         <img src="exibir_imagem.php?id=<?php echo $produto_id; ?>">
        <div style="display:flex; justify-content:center; gap:10px;">
            <button onclick="editar(
                <?php echo $prod['idproduto']; ?>,
                '<?php echo $prod['nome']; ?>',
                '<?php echo $prod['valor']; ?>',
                '<?php echo $prod['quantidade']; ?>',
                '<?php echo $prod['categoria']; ?>',
                '<?php echo $prod['categoria2']; ?>'
            )">
                <i class="fa-solid fa-pen-to-square"></i> Editar
            </button>
            <button onclick="if(confirm('Deseja realmente excluir?')) location.href='produtos_crud.php?excluir=<?php echo $prod['idproduto']; ?>'">
                <i class="fa-solid fa-trash"></i> Excluir
            </button>
        </div>
    </div>
    <?php endwhile; ?>
</main>

<!-- Modal simples para edição -->
<div id="modal-editar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div class="card" style="width:400px;">
        <h2>Editar Produto</h2>
        <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:10px;">
            <input type="hidden" name="id" id="edit-id">
            <input type="text" name="nome" id="edit-nome" placeholder="Nome" required>
            <input type="number" step="0.01" name="valor" id="edit-valor" placeholder="Valor" required>
            <input type="number" name="quantidade" id="edit-quantidade" placeholder="Quantidade" required>
            <input type="text" name="categoria" id="edit-categoria" placeholder="Categoria" required>
            <input type="text" name="categoria2" id="edit-categoria2" placeholder="Categoria 2">
            <input type="file" name="imagem" accept="image/*">
            <button type="submit" name="editar"><i class="fa-solid fa-pen"></i> Salvar</button>
            <button type="button" onclick="fecharModal()" style="background:red;"><i class="fa-solid fa-xmark"></i> Cancelar</button>
        </form>
    </div>
</div>

<script>
function editar(id, nome, valor, quantidade, categoria, categoria2){
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nome').value = nome;
    document.getElementById('edit-valor').value = valor;
    document.getElementById('edit-quantidade').value = quantidade;
    document.getElementById('edit-categoria').value = categoria;
    document.getElementById('edit-categoria2').value = categoria2;
    document.getElementById('modal-editar').style.display = 'flex';
}

function fecharModal(){
    document.getElementById('modal-editar').style.display = 'none';
}
</script>

</body>
</html>
