<?php
session_start();

// Desativar exibição de erros
error_reporting(E_ALL); // Temporariamente, para facilitar a depuração
ini_set('display_errors', 1);

$erro = '';
$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'novaSenha') {
    $novaSenha = trim($_POST['novaSenha']);
    $confirmarSenha = trim($_POST['confirmarSenha']);
    $cpf = $_SESSION['cpf'] ?? ''; // Verificar se o CPF está na sessão

    if (empty($cpf)) {
        $erro = "Sessão inválida. Por favor, reinicie o processo.";
    } elseif (empty($novaSenha) || empty($confirmarSenha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = "As senhas não coincidem.";
    } else {
        // Caminho do arquivo de usuários
        $caminhoArquivo = "../loc/form/usuarios/usuarios.txt";

        // Verifica se o arquivo existe e é legível
        if (!file_exists($caminhoArquivo) || !is_readable($caminhoArquivo)) {
            $erro = "Erro ao acessar o arquivo de usuários.";
        } else {
            // Lê todas as linhas do arquivo
            $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES);
            $senhaAtualizada = false;
            $novoConteudo = [];

            // Percorre as linhas e verifica se o CPF corresponde
            foreach ($linhas as $linha) {
                $dados = explode(",", $linha); // Separar os dados por vírgula
                if ($dados[0] === $cpf) { // Verifica se o CPF corresponde
                    unset($dados[7]); // Remove o elemento do array na posição 7
                    $dados[] = $novaSenha; // Adiciona a nova senha
                    $senhaAtualizada = true; // Marca que a senha foi atualizada
                }
                // Adiciona a linha ao novo conteúdo
                $novoConteudo[] = implode(",", $dados);
            }

            // Verifica se a senha foi realmente atualizada
            if ($senhaAtualizada) {
                // Regrava as linhas de volta no arquivo
                if (file_put_contents($caminhoArquivo, implode(PHP_EOL, $novoConteudo) . PHP_EOL)) {
                    $mensagem = "Senha alterada com sucesso!";
                    unset($_SESSION['cpf']); // Remove o CPF da sessão
                    header("Location: ../home/index.php"); // Redireciona para o login
                    exit;
                } else {
                    $erro = "Erro ao salvar as alterações no arquivo.";
                }
            } else {
                $erro = "Usuário não encontrado para atualizar a senha.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="novasenha.css">
</head>

<body>
    <div class="form-container">
        <img src="./img/shutterstock_1158115840-1.jpg" alt="">
        <form id="formCadastro" action="" method="post">
            <input type="hidden" name="acao" value="novaSenha">
            <h2>Redefinir Senha</h2>
            <div class="inp-senha">
                <label for="novasenha">Nova Senha</label>
                <input type="password" name="novaSenha" id="novasenha" placeholder="Digite sua nova senha" required>
                <label for="confirmarSenha">Confirme Nova Senha</label>
                <input type="password" name="confirmarSenha" id="confirmarSenha" placeholder="Confirme sua nova senha" required>
            </div>
            <div class="buttons"><button type="submit" class="submit-btn"><span></span>Enviar</button></div>

            <?php if (!empty($erro)): ?>
                <div class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem-sucesso"><?php echo htmlspecialchars($mensagem); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>