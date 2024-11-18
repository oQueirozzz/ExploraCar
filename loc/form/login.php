<?php
function emailJaExiste($email, $arquivo) {
    if ($handle = fopen($arquivo, 'r')) {
        while (($linha = fgets($handle)) !== false) {
            list(, $emailArquivo,) = explode('|', trim($linha));
            if ($email === $emailArquivo) {
                fclose($handle);
                return true;
            }
        }
        fclose($handle);
    }
    return false;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registro'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $arquivo = 'usuarios.txt';
 
    if (emailJaExiste($email, $arquivo)) {
        echo "E-mail já cadastrado!";
    } else {
        $dados = "$nome|$email|$senha\n";
        $fp = fopen($arquivo, 'a');
        fwrite($fp, $dados);
        fclose($fp);
       
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario'] = $nome;
        echo "Usuário registrado com sucesso!";
        header("Refresh: 2; url=login-teste.php");
        exit();
    }
}
?>