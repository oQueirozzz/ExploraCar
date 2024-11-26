<?php
session_start();
 
// Desativar exibição de erros
error_reporting(0);
ini_set('display_errors', 0);
 
$erro = '';
$mensagem = '';
$mostrarFormulario = 'login'; // Variável para alternar entre os formulários
 
// Detecta se o usuário clicou em "Esqueci a senha" e muda o formulário a ser exibido
if (isset($_GET['acao']) && $_GET['acao'] === 'esqueceu_senha') {
    $mostrarFormulario = 'esqueceuSenha';
}
 
// Função para verificar login
function verificarLogin($cpf, $senha) {
    $arquivo = fopen("loc/form/usuarios/usuarios.txt", "r");
    if ($arquivo) {
        while (($linha = fgets($arquivo)) !== false) {
            $dados = explode(",", trim($linha));
            if (count($dados) >= 2 && $dados[0] === $cpf && $dados[1] === $senha) {
                fclose($arquivo);
                // Adiciona o nome do usuário à sessão
                $_SESSION['nome'] = $dados[2];
                return true;
            }
        }
        fclose($arquivo);
    }
    return false;
}

// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $cpf = trim($_POST['cpf']);
    $senha = trim($_POST['senha']);
 
    if (empty($cpf) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (verificarLogin($cpf, $senha)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['cpf'] = $cpf;
        header("Location: home/index.php");
        exit;
    } else {
        $erro = "CPF ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha</title>
    <link rel="stylesheet" href="esqueceu.css">

</head>

<body>
    <div class="form-container">
        <form id="formCadastro" action="">
            <h2>Esqueceu sua senha?</h2>
            <div class="inp-email">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Ex: seuemail@gmail.com">
            </div>
            <button type="submit" class="submit-btn">Enviar</button>


            <!-- Div para exibir mensagens -->
            <div id="mensagem"></div>
        </form>
    </div>

    <script>
        window.onload = function () {
            const form = document.querySelector('#formCadastro');
            const email = document.getElementById('email');
            const mensagemDiv = document.getElementById('mensagem');
        
            form.onsubmit = function (event) {
                event.preventDefault();
        
                // Limpar qualquer mensagem anterior
                mensagemDiv.style.display = 'none';
                mensagemDiv.classList.remove('mensagem-sucesso', 'mensagem-erro');
                mensagemDiv.classList.remove('show');
        
                if (!email.value) {
                    // Exibe a mensagem de erro
                    mensagemDiv.textContent = 'Por favor, insira um e-mail válido.';
                    mensagemDiv.classList.add('mensagem-erro');
                    mensagemDiv.classList.add('show');
                    mensagemDiv.style.display = 'block';
                    return;
                }
        
                // Exibe a mensagem de sucesso
                mensagemDiv.textContent = 'Instruções para redefinir a senha foram enviadas para o e-mail: ' + email.value;
                mensagemDiv.classList.add('mensagem-sucesso');
                mensagemDiv.classList.add('show');
                mensagemDiv.style.display = 'block';
        
                // Abrir uma nova página em uma nova aba
                setTimeout(function () {
                    window.open('novasenha.html', '_parent');
                }, 1000); // Espera 1 segundos antes de abrir a nova página
        
                form.reset();
            };
        };
        </script>
        
        
</body>

</html>
