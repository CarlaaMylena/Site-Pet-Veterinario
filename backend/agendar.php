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

$nomePet = sanitize($data['petName'] ?? '');
$nomeTutor = sanitize($data['ownerName'] ?? '');
$email = sanitize($data['email'] ?? '');
$telefone = sanitize($data['phone'] ?? '');
$servico = sanitize($data['service'] ?? '');
$dataAgendamento = sanitize($data['date'] ?? '');
$observacoes = sanitize($data['message'] ?? '');

$erros = [];

if (empty($nomePet)) $erros[] = 'Nome do pet é obrigatório';
if (empty($nomeTutor)) $erros[] = 'Nome do tutor é obrigatório';
if (empty($email) || !validarEmail($email)) $erros[] = 'E-mail inválido';
if (empty($telefone) || !validarTelefone($telefone)) $erros[] = 'Telefone inválido';
if (empty($servico)) $erros[] = 'Serviço é obrigatório';
if (empty($dataAgendamento)) $erros[] = 'Data é obrigatória';

$dataAtual = new DateTime();
$dataEscolhida = new DateTime($dataAgendamento);

if ($dataEscolhida < $dataAtual) {
    $erros[] = 'A data não pode ser no passado';
}

if (!empty($erros)) {
    jsonResponse(false, 'Erros de validação', ['erros' => $erros]);
}

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM agendamentos WHERE data_agendamento = ? AND status != 'cancelado'");
    $stmt->execute([$dataAgendamento]);
    $resultado = $stmt->fetch();
    
    if ($resultado['total'] >= 10) {
        jsonResponse(false, 'Não há vagas para esta data. Escolha outra.');
    }
    
    $stmt = $pdo->prepare("INSERT INTO agendamentos (nome_pet, nome_tutor, email, telefone, servico, data_agendamento, observacoes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pendente')");
    
    $stmt->execute([$nomePet, $nomeTutor, $email, $telefone, $servico, $dataAgendamento, $observacoes]);
    
    jsonResponse(true, 'Agendamento realizado! Entraremos em contato em breve.', [
        'id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log("Erro: " . $e->getMessage());
    jsonResponse(false, 'Erro ao processar. Tente novamente.');
}
?>