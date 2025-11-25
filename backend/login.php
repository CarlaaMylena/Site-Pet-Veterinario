<?php
require_once 'config.php';
session_start();

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

$email = sanitize($data['email'] ?? '');
$senha = $data['password'] ?? '';

if (empty($email) || !validarEmail($email)) {
    jsonResponse(false, 'E-mail inválido');
}

if (empty($senha)) {
    jsonResponse(false, 'Senha obrigatória');
}

try {
    $stmt = $pdo->prepare("SELECT id, nome, email, senha, tipo, ativo FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        jsonResponse(false, 'E-mail ou senha incorretos');
    }
    
    if (!$usuario['ativo']) {
        jsonResponse(false, 'Conta desativada');
    }
    
    if (!password_verify($senha, $usuario['senha'])) {
        jsonResponse(false, 'E-mail ou senha incorretos');
    }
    
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    $_SESSION['logado'] = true;
    
    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
    $stmt->execute([$usuario['id']]);
    
    jsonResponse(true, 'Login realizado!', [
        'usuario' => [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Erro: " . $e->getMessage());
    jsonResponse(false, 'Erro ao processar login');
}
?>