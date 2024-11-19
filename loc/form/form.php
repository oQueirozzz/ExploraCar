<?php
session_start();

// Desativar exibição de erros para evitar mensagens de aviso no navegador
error_reporting(0);
ini_set('display_errors', 0);

// Função para verificar se o CPF já existe no arquivo
function verificarCpfExistente($cpf, $caminhoArquivo) {
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

// Caminho do diretório e arquivo
$diretorio = 'usuarios';
$caminhoArquivo = "$diretorio/usuarios.txt";

// Cria o diretório "usuarios" caso ele não exista
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

// Cria o arquivo caso ele não exista
if (!file_exists($caminhoArquivo)) {
    $arquivo = fopen($caminhoArquivo, "w");
    fclose($arquivo);
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
    $senha = trim($_POST['senha']);
    $confirmaSenha = trim($_POST['confirmaSenha']);

    // Validação dos campos obrigatórios
    if (
        empty($nome) || empty($sobrenome) || empty($pais) || empty($cpf) ||
        empty($dataNascimento) || empty($celular) || empty($email) || empty($senha) || empty($confirmaSenha)
    ) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirmaSenha) {
        $erro = "As senhas não correspondem.";
    } elseif (verificarCpfExistente($cpf, $caminhoArquivo)) {
        $erro = "CPF já cadastrado. Tente fazer login.";
    } else {
        // Formata os dados em uma linha para salvar no arquivo
        $linha = "$cpf,$nome,$sobrenome,$email,$pais,$dataNascimento,$celular,$senha" . PHP_EOL;

        // Salva os dados no arquivo "usuarios.txt"
        if (file_put_contents($caminhoArquivo, $linha, FILE_APPEND | LOCK_EX)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;

            // Redireciona para a página de login
            header("Location: ../home/index.php");
            exit;
        } else {
            $erro = "Não foi possível salvar os dados. Verifique as permissões do arquivo.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crie sua Conta</title>
    <link rel="stylesheet" href="./css/form.css">
</head>

<body>

    <div class="form-container">
        <h2>Crie sua Conta</h2>
       
           <!-- Exibe erros caso existam -->
           <?php if (!empty($erro)) : ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form id="formCadastro" method="POST" action="">
            <!-- Seção: Dados pessoais -->
            <h3>Dados pessoais</h3>
            

            <div class="form-group two-columns">
                <div>
                    <label for="nome">Nome do locatário <span class="required">*</span></label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
                </div>
                <div>
                    <label for="sobrenome">Sobrenome <span class="required">*</span></label>
                    <input type="text" id="sobrenome" name="sobrenome" placeholder="Seu sobrenome" required>
                </div>
            </div>

            <div class="form-group two-columns">
                <div>
                    <label for="pais">País de Residência <span class="required">*</span></label>
                    <select id="pais" name="pais" required>
                        <option value="Brasil">Brasil</option>
                        <option value="Portugal">Portugal</option>
                        <option value="EUA">EUA</option>
                    </select>
                </div>

                <div>
                    <label for="cpf">CPF <span class="required">*</span></label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="form-group two-columns">
                <div>
                    <!-- Data de Nascimento -->
                    <label for="dataNascimento">Data de Nascimento <span class="required">*</span></label>
                    <input type="text" id="birthdate" name="dataNascimento" placeholder="DD/MM/AAAA" required >
                </div>

                <div>
                    <!-- Celular -->
                    <label for="celular">Celular <span class="required">*</span></label>
                    <input type="text" id="celular" name="celular" placeholder="Ex.: (11) 96123-4567" required>
                </div>
            </div>

            <!-- Seção: Dados de acesso à Rentcars -->

            <div class="form-group two-columns">
                <div>
                    <!-- E-mail -->
                    <label for="email">E-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="seuemail@gmail.com" required>
                </div>
                
                <div>
                    <!-- Criar uma Senha de Acesso -->
                    <label for="senha">Crie uma Senha de Acesso <span class="required">*</span></label>
                    <input type="password" id="senha" name="senha" placeholder="Crie sua senha" required>
                </div>

            </div>

            <div class="form-group two-columns">
               

                <div>
                    <!-- Confirmar Senha de Acesso -->
                    <label for="confirmSenha">Confirme sua Senha de Acesso <span class="required">*</span></label>
                    <input type="password" id="confirmSenha" name="confirmaSenha" placeholder="Confirme sua senha" required>
                </div>
            </div>

            <!-- Política de Privacidade -->
            <div class="form-footer">
                <label>
                    <input type="checkbox" name="politicaPrivacidade" required>
                    Ao gerar uma reserva você concorda com a <a href="../politicas/politicas.html">Política de Privacidade</a> da
                    ExploraCar
                </label>
            </div>

            <!-- Ofertas e promoções -->
            <div class="form-footer">
                <label>
                    <input type="checkbox" name="ofertas">
                    Aceito receber todas as ofertas e promoções da ExploraCar
                </label>
            </div>

            <div class="form-group two-columns">
                <!-- Botão de envio -->
                <input type="submit" class="submit-btn" value="Criar Conta"></input>

                <!-- Notas de rodapé -->
                <div class="footer-notes">
                    ✔ Rápido e fácil reservar<br>
                    ✔ Descontos de até 30%<br>
                    ✔ Acesso a ofertas exclusivas
                </div>
            </div>
        </form>
    </div>

    <script>
        window.onload = function () {
            const form = document.querySelector('#formCadastro'); // Corrigido para selecionar o form pelo ID
            const password = document.getElementById('senha');
            const confirmPassword = document.getElementById('confirmSenha');

            form.onsubmit = function (event) {
                event.preventDefault();

                if (!password.value || !confirmPassword.value) {
                    alert('Por favor, preencha os dois campos de senha.');
                    return;
                }

                if (password.value !== confirmPassword.value) {
                    alert('As senhas não coincidem.');
                    return;
                }

                alert('Login efetuado com sucesso!');
                form.submit();
                window.location.href = '../index.html';
            };

            const telefone = document.querySelector('#celular'); // Corrigido para o ID "celular"
            if (telefone) {
                telefone.addEventListener('keyup', () => {
                    let valor = telefone.value.replace(/\D+/g, '').slice(0, 11);
                    valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                    telefone.value = valor;
                });
            }

            const cpf = document.querySelector('#cpf'); // Máscara para o CPF
            if (cpf) {
                cpf.addEventListener('input', () => {
                    let valor = cpf.value.replace(/\D+/g, '').slice(0, 11);
                    valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    cpf.value = valor;
                });
            }
        }; 

        document.getElementById("cnh").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
    value = value.replace(/(\d{3})(\d)/, "$1 $2"); // Adiciona o primeiro espaço
    value = value.replace(/(\d{3}) (\d{3})(\d)/, "$1 $2 $3"); // Adiciona o segundo espaço
    value = value.replace(/(\d{3}) (\d{3}) (\d{3})(\d)/, "$1 $2 $3 $4"); // Adiciona o último espaço
    e.target.value = value;
});


document.getElementById("birthdate").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, ""); // Remove todos os caracteres não numéricos
    value = value.replace(/(\d{2})(\d)/, "$1/$2"); // Adiciona a primeira barra
    value = value.replace(/(\d{2})\/(\d{2})(\d)/, "$1/$2/$3"); // Adiciona a segunda barra
    value = value.substring(0, 10); // Limita o tamanho máximo a 10 caracteres
    e.target.value = value;
});
        

    </script>

   
</body>

</html>