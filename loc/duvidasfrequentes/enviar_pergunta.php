<?php
// Define o cabeçalho como JSON para a resposta AJAX
header('Content-Type: application/json');

// Verifica se a pergunta foi enviada via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém a pergunta do formulário
    $pergunta = trim($_POST['question']);

    // Verifica se a pergunta não está vazia
    if (!empty($pergunta)) {
        // Define o caminho do arquivo onde as perguntas serão armazenadas
        $arquivo = 'perguntas.txt';

        // Adiciona a pergunta no arquivo, com uma nova linha
        if (file_put_contents($arquivo, $pergunta . PHP_EOL, FILE_APPEND | LOCK_EX)) {
            // Retorna uma resposta de sucesso em formato JSON
            echo json_encode(['success' => true, 'question' => $pergunta]);
        } else {
            // Retorna uma resposta de erro caso não seja possível salvar a pergunta
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar a pergunta.']);
        }
    } else {
        // Retorna uma resposta de erro caso a pergunta esteja vazia
        echo json_encode(['success' => false, 'message' => 'Por favor, escreva uma pergunta antes de enviar.']);
    }
} else {
    // Retorna uma resposta de erro para método de envio inválido
    echo json_encode(['success' => false, 'message' => 'Método de envio inválido.']);
}
?>

