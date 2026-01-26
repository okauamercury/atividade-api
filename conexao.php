<?php

header("Access-Control-Allow-Origin: http://acessoapp.com*");
header("Access-Control-Allow-Credentials: true");


header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$host = "10.91.47.129";
$user = "root";
$pass = "202720";
$banco = "comercialtdsdb01";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'msg' => 'Erro interno ao conectar o banco',
        'erro' => $e
    ]);
    exit();
}
