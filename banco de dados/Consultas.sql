-- 1. Listar todos os clientes
SELECT * FROM cliente;

-- 2. Listar todos os produtos disponíveis
SELECT nome, valor, quantidade, categoria, categoria2 FROM produto;

-- 3. Listar apenas os produtos da categoria "Bebidas"
SELECT nome, valor FROM produto WHERE categoria = 'Bebidas';

-- 4. Mostrar o carrinho de cada cliente
SELECT c.nome AS cliente, p.nome AS produto, ca.quantidade_carrinho
FROM carrinho ca
JOIN cliente c ON ca.cliente_idcliente = c.idcliente
JOIN produto p ON ca.produto_idproduto = p.idproduto
ORDER BY c.nome;

-- 5. Mostrar todos os pedidos realizados
SELECT 
    pe.idpedido, 
    c.nome AS cliente, 
    p.nome AS produto, 
    pe.`quantidade-ped` AS quantidade,
    pe.`valor total` AS valor_total, 
    pe.`data-ent` AS data_ent
FROM pedido pe
JOIN cliente c ON pe.cliente_idcliente = c.idcliente
JOIN produto p ON pe.produto_idproduto = p.idproduto
ORDER BY pe.`data-ent`;

-- 6. Total gasto por cada cliente
SELECT 
    c.nome AS cliente, 
    SUM(pe.`valor total`) AS total_gasto
FROM pedido pe
JOIN cliente c ON pe.cliente_idcliente = c.idcliente
GROUP BY c.nome
ORDER BY total_gasto DESC;

-- 7. Produtos mais vendidos
SELECT 
    p.nome AS produto, 
    SUM(pe.`quantidade-ped`) AS total_vendido
FROM pedido pe
JOIN produto p ON pe.produto_idproduto = p.idproduto
GROUP BY p.nome
ORDER BY total_vendido DESC;

-- 8. Funcionários cadastrados
SELECT nome_func, email_func, genero_func FROM funcionarios;

-- 9. Pedidos feitos em uma data específica (exemplo: 2025-09-03)
SELECT 
    pe.idpedido, 
    c.nome AS cliente, 
    p.nome AS produto, 
    pe.`valor total` AS valor_total
FROM pedido pe
JOIN cliente c ON pe.cliente_idcliente = c.idcliente
JOIN produto p ON pe.produto_idproduto = p.idproduto
WHERE pe.`data-ent` = '2025-09-03';

-- 10. Média de valor gasto por pedido
SELECT AVG(`valor total`) AS media_valor_pedido FROM pedido;
