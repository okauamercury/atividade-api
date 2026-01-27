<?php

include_once('conexao.php');
$postjson = json_decode(file_get_contents("php://input"), true);
$response = ['success' => false];
if (!isset($postjson['requisicao'])) {
    echo json_encode(['success' => false, 'msg' => 'Requisição Invalida']);
    exit();
}

switch ($postjson['requisicao']) {
    case 'add-cliente': 

        if (empty($postjson['nome']) || empty($postjson['cpf']) || empty($postjson['telefone']) || empty($postjson['email']) || empty($postjson['data_nasc'])) {
            $response['msg'] = 'Dados incompletos para adicionar o cliente.';
            break;
        }
        $stmt = $pdo->prepare(
            "CALL sp_cliente_insert(:nome, :cpf, :telefone, :email, :data_nasc)"
        );
        $stmt->execute([
            ':nome' => $postjson['nome'],
            ':cpf' => $postjson['cpf'],
            ':telefone' => $postjson['telefone'],
            ':email' => $postjson['email'],
            ':data_nasc' => $postjson['data_nasc'],
        ]);
        $response = [
            'success' => true,
            'msg' => 'Cliente adicionado com sucesso.',
        ];
        break;

    // Atualizar cliente
       case 'atualizar_cliente':
        if (empty($postjson['id']) ||empty($postjson['nome']) || empty($postjson['telefone']) || empty($postjson['data_nasc'])) {
             $response['msg'] = 'Dados incompletos para atualizar o cliente.';
            break;
        }
        $stmt = $pdo->prepare(
            "call sp_cliente_update(:id, :nome, :telefone, :data_nasc)"
        );
        $stmt->execute([
            ':id' => $postjson['id'],
            ':nome' => $postjson['nome'],
            ':telefone' => $postjson['telefone'],
            ':data_nasc' => $postjson['data_nasc'],
        ]);
        $response = [
            'success' => true,
            'msg' => 'Cliente atualizado com sucesso'
        ];
        break;
}
echo json_encode($response);
