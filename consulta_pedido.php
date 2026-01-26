<?php

include_once ('conexao.php');
$postjson = json_decode(file_get_contents("php://input"), true);
$response = ['success' => false];
if (!isset($postjson['requisicao'])) {
    echo json_encode(['success' => false, 'msg' => 'Requisição Invalida']);
    exit();
}