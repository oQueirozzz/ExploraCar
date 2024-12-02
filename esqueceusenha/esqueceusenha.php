<?php
session_start();

// Desativar exibição de erros
error_reporting(0);
ini_set('display_errors', 0);

$erro = '';
$mensagem = '';
$arquivo = file("../loc/form/usuarios/usuarios.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

?>
<div class="error-email">
<?php
    if ($arquivo === false) {
        die("Erro: Não foi possível abrir o arquivo usuarios.txt.");
    }
        ?>
</div>

<?php

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'esqueceuSenha') {
    $email = trim($_POST['email']);
    $encontrouEmail = false;

    $caminhoArquivo = "../loc/form/usuarios/usuarios.txt";

    // Certifique-se de que o arquivo existe e pode ser lido
    if (!file_exists($caminhoArquivo) || !is_readable($caminhoArquivo)) {
        die("Erro: Não foi possível abrir o arquivo usuarios.txt.");
    }

    // Lê o arquivo linha por linha
    $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Itera sobre cada linha para encontrar o email
    foreach ($linhas as $linha) {
        $dados = explode(",", $linha); // Divide os campos da linha
        if (isset($dados[3]) && $dados[3] === $email) { // Verifica se o email está na posição 3
            $encontrouEmail = true;
            $_SESSION['cpf'] = $dados[0]; // Armazena o CPF na sessão para o próximo passo
            break;
        }
    }

    if ($encontrouEmail) {
        $mensagem = "E-mail encontrado! Redirecionando para redefinição de senha...";
        header("Location: novasenha.php"); // Redireciona para a página de redefinição
        exit;
    } else {
        $erro = "E-mail não encontrado. Por favor, verifique e tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha</title>
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="esqueceu.css">
</head>

<body>
    <div class="form-container">
        <img src="./img/OIP.jfif" alt="">
        <form id="formCadastro" action="" method="post">
            <input type="hidden" name="acao" value="esqueceuSenha">
            <h2>Esqueceu sua senha?</h2>
            <div class="inp-email">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Ex: seuemail@gmail.com" required>
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