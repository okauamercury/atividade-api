<?php

include_once('conexao.php');

$postjson = json_decode(file_get_contents("php://input"), true);
$response = ['success' => false];
if (!isset($postjson['requisicao'])) {
    echo json_encode(['success' => false, 'msg' => 'Requisição Invalida']);
    exit();
}

// registro, manutenção e consulta de itens de pedidos

switch ($postjson['requisicao']) {
    // registrar item de pedido
    case 'add_item':
        if (empty($postjson['pedido_id']) || empty($postjson['produto_id']) || empty($postjson['quantidade']) || !isset($postjson['desconto'])) {
            break;
        }
        $stmt = $pdo->prepare(
            "call sp_itempedido_insert (:pedido_id, :produto_id, :quantidade, :desconto)"
        );
        $stmt->execute([
            ':pedido_id' => $postjson ['pedido_id'],
            ':produto_id' => $postjson ['produto_id'],
            ':quantidade' => $postjson ['quantidade'],
            ':desconto' => $postjson ['desconto'],
        ]);
       $response = [
            'success' => true,
            'msg' => 'Item de Pedido registrado com sucesso',
            'id' => $pdo->lastInsertId()
        ];      
        break;


    // atualizar item de pedido
    case 'atualizar_item':
        if (empty($postjson['item_id']) || empty($postjson['quantidade']) || empty($postjson['desconto'])) {
            break;
        }
        $stmt = $pdo->prepare(
            "call sp_itempedido_update(:item_id, :quantidade, :desconto)"
        );
        $stmt->execute([
            ':item_id' => $postjson['item_id'],
            ':quantidade' => $postjson['quantidade'],
            ':desconto' => $postjson['desconto'],
        ]);
        $response = [
            'success' => true,
            'msg' => 'Item de pedido atualizado com sucesso'
        ];
        break;
    
        // consultar itens de pedidos
    case 'consultar_item':
        $stmt = $pdo->prepare("select * from vw_pedido");
        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = [
            'success' => true,
            'result' => $dados
        ];
        break;

        // excluir item de pedido
    case 'excluir_item':
        if (empty($postjson['item_id'])) {
            break;
        }
        $stmt = $pdo->prepare("call sp_itempedido_delete(:item_id)");
        $stmt->execute([
            ':item_id' => $postjson['item_id']
        ]);
        $response = [
            'success' => true,
            'msg' => 'Item de pedido deletado com sucesso'
        ];
        break;
}
echo json_encode($response);






        