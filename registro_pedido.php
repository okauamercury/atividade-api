<?php

include_once('conexao.php');

$postjson = json_decode(file_get_contents("php://input"), true);
$response = ['success' => false];

if (!isset($postjson['requisicao'])) {
    echo json_encode(['success' => false, 'msg' => 'Requisição Invalida']);
    exit();
}


// registro, manutenção e consulta de pedidos

switch ($postjson['requisicao']) {
    // registrar pedido
    case 'registro_pedido':
        if (empty($postjson['usuario_id']) || empty($postjson['cliente_id'])) {
            break;
        }
        $stmt = $pdo->prepare(
            "call sp_pedido_insert(:usuario_id, :cliente_id)"
        );
        $stmt->execute([
            ':usuario_id' => $postjson['usuario_id'],
            ':cliente_id' => $postjson['cliente_id'],
        ]);
        $response = [
            'success' => true,
            'msg' => 'Pedido registrado com sucesso',
            'id' => $pdo->lastInsertId()
        ];
        break;       
    default:
        $response = ['success' => false, 'msg' => 'Requisição Invalida'];
        break;

    // atualizar pedido
    case 'atualizar_pedido':
        if (empty($postjson['pedido_id']) || empty($postjson['status']) || empty($postjson['desconto'])) {
            break;
        }
        $stmt = $pdo->prepare(
            "call sp_pedido_update(:pedido_id, :status, :desconto)"
        );
        $stmt->execute([
            ':pedido_id' => $postjson['pedido_id'],
            ':status' => $postjson['status'],
            ':desconto' => $postjson['desconto'],
        ]);
        $response = [
            'success' => true,
            'msg' => 'Pedido atualizado com sucesso'
        ];
        break;
    
        // consultar pedidos
    case 'consultar_pedidos':
        $stmt = $pdo->prepare("select * from vw_pedido");
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = [
            'success' => true,
            'pedidos' => $pedidos
        ];
        break;

}
echo json_encode($response);


