<?php
session_start();

// Verifica se está logado e se é admin/funcionario
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != "admin"){
    header("Location: ../login.html");
    exit;
}

include '../cb.php'; // Conexão com o banco

// Mensagens
$mensagem = "";

// Cadastro de produto
if(isset($_POST['cadastrar'])){
    $nome = $conexao->real_escape_string($_POST['nome']);
    $valor = $conexao->real_escape_string($_POST['valor']);
    $quantidade = $conexao->real_escape_string($_POST['quantidade']);
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $categoria2 = $conexao->real_escape_string($_POST['categoria2']);
    $imagem = $conexao->real_escape_string($_POST['imagem']);

    $sql = "INSERT INTO produto (nome, valor, quantidade, categoria, categoria2, imagem) 
            VALUES ('$nome', '$valor', '$quantidade', '$categoria', '$categoria2', '$imagem')";
    if($conexao->query($sql)){
        $mensagem = "Produto cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar: " . $conexao->error;
    }
}

// Exclusão de produto
if(isset($_GET['excluir'])){
    $id = intval($_GET['excluir']);
    $sql = "DELETE FROM produto WHERE idproduto = $id";
    $conexao->query($sql);
    header("Location: produtos_crud.php");
    exit;
}

// Edição de produto
if(isset($_POST['editar'])){
    $id = intval($_POST['id']);
    $nome = $conexao->real_escape_string($_POST['nome']);
    $valor = $conexao->real_escape_string($_POST['valor']);
    $quantidade = $conexao->real_escape_string($_POST['quantidade']);
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $categoria2 = $conexao->real_escape_string($_POST['categoria2']);
    $imagem = $conexao->real_escape_string($_POST['imagem']);

    $sql = "UPDATE produto SET 
            nome='$nome', 
            valor='$valor', 
            quantidade='$quantidade', 
            categoria='$categoria', 
            categoria2='$categoria2', 
            imagem='$imagem' 
            WHERE idproduto=$id";

    if($conexao->query($sql)){
        $mensagem = "Produto atualizado com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar: " . $conexao->error;
    }
    header("Location: produtos_crud.php");
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
    <style>
        .produto-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            background: white;
        }
        .produto-imagem {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .valor {
            color: #2ecc71;
            font-weight: bold;
            font-size: 1.2em;
        }
        .estoque {
            color: #e74c3c;
            font-weight: bold;
        }
        .categoria {
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<header>
    <div class="header-top">
        <h1>Gestão de Produtos</h1>
        <p>Bem-vindo, <?php echo $_SESSION['nome']; ?> | <a href="../logout.php">Sair</a></p>
    </div>
    <nav class="header-nav">
        <a href="funcionarios_crud.php" class="nav-btn"><i class="fa-solid fa-user"></i> Funcionários</a>
        <a href="produtos_crud.php" class="nav-btn active"><i class="fa-solid fa-box"></i> Produtos</a>
        <a href="pedidos_crud.php" class="nav-btn"><i class="fa-solid fa-clipboard-list"></i> Pedidos</a>
    </nav>
</header>

<main style="padding: 20px;">
    <?php if($mensagem) echo "<p style='text-align:center; color:green; font-weight:bold;'>$mensagem</p>"; ?>

    <!-- Formulário de cadastro -->
    <div class="card">
        <h2><i class="fa-solid fa-plus"></i> Cadastrar Novo Produto</h2>
        <form method="POST" style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            <div>
                <label>Nome do Produto:</label>
                <input type="text" name="nome" placeholder="Nome do produto" required>
            </div>
            <div>
                <label>Valor (R$):</label>
                <input type="number" name="valor" step="0.01" placeholder="0.00" required>
            </div>
            <div>
                <label>Quantidade em Estoque:</label>
                <input type="number" name="quantidade" placeholder="Quantidade" required>
            </div>
            <div>
                <label>Categoria Principal:</label>
                <input type="text" name="categoria" placeholder="Categoria" required>
            </div>
            <div>
                <label>Categoria Secundária:</label>
                <input type="text" name="categoria2" placeholder="Subcategoria">
            </div>
            <div>
                <label>URL da Imagem:</label>
                <input type="text" name="imagem" placeholder="http://exemplo.com/imagem.jpg">
            </div>
            <div style="grid-column: 1 / -1;">
                <button type="submit" name="cadastrar" style="background: #27ae60;">
                    <i class="fa-solid fa-save"></i> Cadastrar Produto
                </button>
            </div>
        </form>
    </div>

    <!-- Listagem de produtos -->
    <h2 style="margin-top: 30px;"><i class="fa-solid fa-list"></i> Produtos Cadastrados</h2>
    
    <?php if($result->num_rows > 0): ?>
        <?php while($produto = $result->fetch_assoc()): ?>
        <div class="produto-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p class="valor">R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></p>
                    <p class="estoque">Estoque: <?php echo $produto['quantidade']; ?> unidades</p>
                    <p>
                        <span class="categoria"><?php echo $produto['categoria']; ?></span>
                        <?php if(!empty($produto['categoria2'])): ?>
                            <span class="categoria" style="background: #9b59b6;"><?php echo $produto['categoria2']; ?></span>
                        <?php endif; ?>
                    </p>
                    <?php if(!empty($produto['imagem'])): ?>
                        <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="produto-imagem">
                    <?php endif; ?>
                </div>
                
                <div style="display: flex; gap: 10px; flex-direction: column;">
                    <button onclick="editarProduto(
                        <?php echo $produto['idproduto']; ?>,
                        '<?php echo addslashes($produto['nome']); ?>',
                        '<?php echo $produto['valor']; ?>',
                        '<?php echo $produto['quantidade']; ?>',
                        '<?php echo addslashes($produto['categoria']); ?>',
                        '<?php echo addslashes($produto['categoria2']); ?>',
                        '<?php echo addslashes($produto['imagem']); ?>'
                    )" style="background: #f39c12;">
                        <i class="fa-solid fa-pen-to-square"></i> Editar
                    </button>
                    <button onclick="if(confirm('Tem certeza que deseja excluir o produto <?php echo addslashes($produto['nome']); ?>?')) location.href='produtos_crud.php?excluir=<?php echo $produto['idproduto']; ?>'" style="background: #e74c3c;">
                        <i class="fa-solid fa-trash"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card">
            <p style="text-align: center; color: #7f8c8d;">Nenhum produto cadastrado.</p>
        </div>
    <?php endif; ?>
</main>

<!-- Modal para edição -->
<div id="modal-editar" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div class="card" style="width:500px; max-height:90vh; overflow-y:auto;">
        <h2><i class="fa-solid fa-pen"></i> Editar Produto</h2>
        <form method="POST" style="display:flex; flex-direction:column; gap:10px;">
            <input type="hidden" name="id" id="edit-id">
            
            <label>Nome do Produto:</label>
            <input type="text" name="nome" id="edit-nome" placeholder="Nome do produto" required>
            
            <label>Valor (R$):</label>
            <input type="number" name="valor" id="edit-valor" step="0.01" placeholder="0.00" required>
            
            <label>Quantidade em Estoque:</label>
            <input type="number" name="quantidade" id="edit-quantidade" placeholder="Quantidade" required>
            
            <label>Categoria Principal:</label>
            <input type="text" name="categoria" id="edit-categoria" placeholder="Categoria" required>
            
            <label>Categoria Secundária:</label>
            <input type="text" name="categoria2" id="edit-categoria2" placeholder="Subcategoria">
            
            <label>URL da Imagem:</label>
            <input type="text" name="imagem" id="edit-imagem" placeholder="http://exemplo.com/imagem.jpg">
            
            <div style="display:flex; gap:10px; justify-content:center;">
                <button type="submit" name="editar" style="background: #27ae60;">
                    <i class="fa-solid fa-save"></i> Salvar Alterações
                </button>
                <button type="button" onclick="fecharModal()" style="background: #e74c3c;">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editarProduto(id, nome, valor, quantidade, categoria, categoria2, imagem){
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nome').value = nome;
    document.getElementById('edit-valor').value = valor;
    document.getElementById('edit-quantidade').value = quantidade;
    document.getElementById('edit-categoria').value = categoria;
    document.getElementById('edit-categoria2').value = categoria2;
    document.getElementById('edit-imagem').value = imagem;
    document.getElementById('modal-editar').style.display = 'flex';
}

function fecharModal(){
    document.getElementById('modal-editar').style.display = 'none';
}

// Fechar modal clicando fora
document.getElementById('modal-editar').addEventListener('click', function(e) {
    if (e.target.id === 'modal-editar') {
        fecharModal();
    }
});
</script>

</body>
</html>