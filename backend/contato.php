<?php
require_once 'config.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    jsonResponse(false, 'Dados inválidos');
}

$nome = sanitize($data['name'] ?? '');
$email = sanitize($data['email'] ?? '');
$telefone = sanitize($data['phone'] ?? '');
$mensagem = sanitize($data['message'] ?? '');

$erros = [];

if (empty($nome) || strlen($nome) < 3) $erros[] = 'Nome muito curto';
if (empty($email) || !validarEmail($email)) $erros[] = 'E-mail inválido';
if (empty($telefone) || !validarTelefone($telefone)) $erros[] = 'Telefone inválido';
if (empty($mensagem) || strlen($mensagem) < 10) $erros[] = 'Mensagem muito curta';

if (!empty($erros)) {
    jsonResponse(false, 'Erros de validação', ['erros' => $erros]);
}

try {
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $email, $telefone, $mensagem]);
    
    jsonResponse(true, 'Mensagem enviada! Retornaremos em breve.', [
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log("Erro: " . $e->getMessage());
    jsonResponse(false, 'Erro ao enviar. Tente novamente.');
}
?>