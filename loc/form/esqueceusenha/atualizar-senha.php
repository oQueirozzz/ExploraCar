<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $novaSenha = trim($_POST['novasenha']);
    $arquivo = 'usuarios.txt';
    $usuarios = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Verifica se o arquivo foi carregado
    if (!$usuarios) {
        echo "Erro ao acessar o arquivo de usuários.";
        exit;
    }

    $alterado = false;
    $novosUsuarios = [];

    // Busca o usuário e altera a senha
    foreach ($usuarios as $linha) {
        $dados = explode(',', $linha);
        $emailCadastrado = trim($dados[3]);

        if ($emailCadastrado === $email) {
            // Substitui a senha (supondo que a senha seja o último campo)
            $dados[7] = $novaSenha;  // Altere conforme o índice correto
            $linha = implode(',', $dados);
            $alterado = true;
        }
        $novosUsuarios[] = $linha;
    }

    // Se a senha foi alterada, salva no arquivo
    if ($alterado) {
        file_put_contents($arquivo, implode("\n", $novosUsuarios) . "\n");
        echo "Senha atualizada com sucesso!";
    } else {
        echo "Usuário não encontrado.";
    }
}
?>
