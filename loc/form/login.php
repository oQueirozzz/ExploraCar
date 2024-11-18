<?php
session_start();

// Desativar exibição de erros para evitar mensagens de aviso no navegador
error_reporting(0);
ini_set('display_errors', 0);

// Função para verificar se o CPF já existe no arquivo
function verificarCpfExistente($cpf) {
    $caminhoArquivo = "usuarios/usuarios.txt";
    if (file_exists($caminhoArquivo)) {
        $arquivo = fopen($caminhoArquivo, "r");
        if ($arquivo) {
            while (($linha = fgets($arquivo)) !== false) {
                $dados = explode(",", trim($linha));
                if (count($dados) >= 2 && $dados[0] === $cpf) {
                    fclose($arquivo);
                    return true;
                }
            }
            fclose($arquivo);
        }
    }
    return false;
}

$erro = '';

// Cria o diretório "usuarios" caso ele não exista
if (!is_dir('usuarios')) {
    mkdir('usuarios', 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura os dados enviados pelo formulário
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $pais = trim($_POST['pais']);
    $cpf = trim($_POST['cpf']);
    $dataNascimento = trim($_POST['dataNascimento']);
    $celular = trim($_POST['celular']);
    $email = trim($_POST['email']);
    $confirmEmail = trim($_POST['confirmEmail']);
    $senha = trim($_POST['senha']);
    $confirmaSenha = trim($_POST['confirmaSenha']);

    // Validação dos campos obrigatórios
    if (
        empty($nome) || empty($sobrenome) || empty($pais) || empty($cpf) ||
        empty($dataNascimento) || empty($celular) || empty($email) ||
        empty($confirmEmail) || empty($senha) || empty($confirmaSenha)
    ) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirmaSenha) {
        $erro = "As senhas não correspondem.";
    } elseif ($email !== $confirmEmail) {
        $erro = "Os e-mails não correspondem.";
    } elseif (verificarCpfExistente($cpf)) {
        $erro = "CPF já cadastrado. Tente fazer login.";
    } else {
        // Formata todos os dados do formulário
        $dados = [
            'Nome' => $nome,
            'Sobrenome' => $sobrenome,
            'País' => $pais,
            'CPF' => $cpf,
            'Data de Nascimento' => $dataNascimento,
            'Celular' => $celular,
            'E-mail' => $email,
        ];

        // Cria uma string formatada para salvar no arquivo
        $conteudo = '';
        foreach ($dados as $chave => $valor) {
            $conteudo .= "$chave: $valor" . PHP_EOL;
        }
        $conteudo .= "-----------------------------" . PHP_EOL;

        // Caminho do arquivo
        $caminhoArquivo = "usuarios/usuarios.txt";

        // Salva os dados no arquivo "usuarios.txt"
        if (file_put_contents($caminhoArquivo, $conteudo, FILE_APPEND | LOCK_EX)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;

            // Redireciona para a página de login
            header("Location: login.php");
            exit;
        } else {
            $erro = "Não foi possível salvar os dados. Tente novamente.";
        }
    }
}
?>
