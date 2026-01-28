<?php
$plano = 'A'; // ou 'B' ou 'C'
$cpf = '12345678901';
$apiKey = 'SUA_CHAVE_API_AQUI';

include_once('conexao.php');


header('Content-Type: application/json; charset=utf-8');

$apiKey = "consulta_cpf";
$url = "https://api.cpfhub.io/api/cpf";


$cpf = "506.809.568-08";
$birthDate = "17/02/2004";


$cpf = preg_replace('/\D/', '', $cpf);


if (strlen($cpf) !== 11) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'CPF inválido'
    ]);
    exit;
}


$payload = json_encode([
    "cpf" => $cpf,
    "birthDate" => $birthDate
]);


$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-api-key: {$apiKey}"
    ],
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);


if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro de comunicação com a API',
        'details' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


$result = json_decode($response, true);


if (!$result) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Resposta inválida da API externa'
    ]);
    exit;
}


http_response_code($httpCode);
echo json_encode([
    'status' => 'success',
    'data' => $result
]);
$postjson = json_decode(file_get_contents("php://input"), true);
$response = ['success' => false];
