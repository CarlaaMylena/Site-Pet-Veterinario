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
$senha = $data['password'] ?? '';
$confirmarSenha = $data['confirm_password'] ?? '';

$erros = [];

if (empty($nome) || strlen($nome) < 3) $erros[] = 'Nome muito curto';
if (empty($email) || !validarEmail($email)) $erros[] = 'E-mail inválido';
if (empty($telefone) || !validarTelefone($telefone)) $erros[] = 'Telefone inválido';
if (empty($senha) || strlen($senha) < 6) $erros[] = 'Senha deve ter 6+ caracteres';
if ($senha !== $confirmarSenha) $erros[] = 'Senhas não coincidem';

if (!empty($erros)) {
    jsonResponse(false, 'Erros de validação', ['erros' => $erros]);
}

try {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        jsonResponse(false, 'E-mail já cadastrado');
    }
    
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, tipo) VALUES (?, ?, ?, ?, 'cliente')");
    $stmt->execute([$nome, $email, $telefone, $senhaHash]);
    
    jsonResponse(true, 'Cadastro realizado! Faça login.', [
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log("Erro: " . $e->getMessage());
    jsonResponse(false, 'Erro ao cadastrar');
}
?>