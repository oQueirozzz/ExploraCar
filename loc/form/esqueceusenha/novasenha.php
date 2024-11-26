<?php
if (!isset($_GET['email'])) {
    echo "Erro: Nenhum e-mail fornecido.";
    exit;
}

$email = htmlspecialchars($_GET['email']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="novasenha.css">
</head>

<body>
    <div class="form-container">
        <form id="formCadastro" action="atualizar_senha.php" method="post">
        <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

            <h2>Redefinir Senha</h2>
            <div class="inp-senha">
                <label for="novasenha">Nova Senha</label>
                <input type="password" name="novasenha" id="novasenha" placeholder="Digite sua nova senha" required>
                <label for="confsenha">Confirme Nova Senha</label>
                <input type="password" name="confsenha" id="confsenha" placeholder="Confirme sua nova senha" required>
            </div>
            <button type="submit" class="submit-btn">Enviar</button>

            <!-- Div para exibir mensagens -->
            <div id="mensagem"></div>
        </form>
    </div>
</body>

</html>
