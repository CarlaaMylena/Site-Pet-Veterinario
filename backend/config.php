<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'petshop_planvet');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_URL', 'http://localhost/site_pet_veterinaria');
define('SITE_NAME', 'PetShop Planvet');
define('SITE_EMAIL', 'administracao@planvetsaude.com.br');

date_default_timezone_set('America/Fortaleza');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>