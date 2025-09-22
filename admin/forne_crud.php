<?php
session_start();

// Verifica se está logado e se é funcionário
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != "funcionario"){
    header("Location: ../login.html");
    exit;
}

include '../cb.php'; // Conexão com o banco

// Consulta os fornecedores
$fornecedores = $conexao->query("SELECT * FROM fornecedores");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores</title>
    <link rel="stylesheet" href="css/ped.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <header>
        <a href="dashboard.php" class="btn-voltar">← Voltar</a>
        <h1>Fornecedores</h1>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if($fornecedores && $fornecedores->num_rows > 0): ?>
                    <?php while($row = $fornecedores->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($row['endereco']); ?></td>
                            <td>
                                <a class="btn-acao" href="fornecedor_update.php?id=<?php echo $row['id']; ?>">Editar</a>
                                <a class="btn-acao btn-excluir" href="fornecedor_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Deseja realmente excluir?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">Nenhum fornecedor encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
