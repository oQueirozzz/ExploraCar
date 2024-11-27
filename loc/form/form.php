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
    $birthdate = trim($_POST['birthdate']);
    $celular = trim($_POST['celular']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmaSenha = trim($_POST['confirmaSenha']);
    

    // Remove espacos em branco
    $nome = preg_replace('/^\s+|\s+$/m', '', $nome);
    $sobrenome = preg_replace('/^\s+|\s+$/m', '', $sobrenome);
    $pais = preg_replace('/^\s+|\s+$/m', '', $pais);
    $birthdate = preg_replace('/^\s+|\s+$/m', '', $birthdate);
    $email = preg_replace('/^\s+|\s+$/m', '', $email);
    $senha = preg_replace('/^\s+|\s+$/m', '', $senha);
    $confirmaSenha = preg_replace('/^\s+|\s+$/m', '', $confirmaSenha);
    $cpf = preg_replace('/\D+/', '', $cpf); // Remove caracteres não numéricos
$cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);

$celular = preg_replace('/\D+/', '', $celular); // Remove caracteres não numéricos
$celular = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular);


    echo $nome;
    echo $sobrenome;
    echo $pais;
    echo $cpf;
    echo $birthdate;
    echo $celular;
    echo $email;
    echo $senha;
    echo $confirmaSenha;

    // Validação dos campos obrigatórios
    if (
        empty($nome) || empty($sobrenome) || empty($pais) || empty($cpf) ||
        empty($birthdate) || empty($celular) || empty($email) || empty($senha) || empty($confirmaSenha)
    ) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirmaSenha) {
        $erro = "As senhas não correspondem.";
    } elseif (verificarCpfExistente($cpf, $caminhoArquivo)) {
        $erro = "CPF já cadastrado. Tente fazer login.";
    } else {
        // Formata os dados em uma linha para salvar no arquivo
        $linha = "$cpf,$nome,$sobrenome,$email,$pais,$birthdate,$celular,$senha" . PHP_EOL;

        // Salva os dados no arquivo "usuarios.txt"
        if (file_put_contents($caminhoArquivo, $linha, FILE_APPEND | LOCK_EX)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;

            // Redireciona para a página de login
            header("Location: ../../home/index.php");
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
    <link rel="stylesheet" href="../../global/global.css">
    <link rel="stylesheet" href="./css/form.css">
</head>

<body>

    <main>
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
                            <input type="text" id="birthdate" name="birthdate" placeholder="DD/MM/AAAA" required >
                            <div id="birthdateError" class="error-message" style="display:none;">Por favor, insira uma data de nascimento válida.</div>
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
                    <div class="form-footer-cont">
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
                    </div>
                    <div class="form-group two-columns">
                        <!-- Botão de envio -->
                        <div class="buttons"><button type="submit" id="cadastrar-button" class="login-button"><span></span>Criar Conta</button></div>
                        <!-- Notas de rodapé -->
                        <div class="footer-notes">
                            ✔ Rápido e fácil reservar<br>
                            ✔ Descontos de até 30%<br>
                            ✔ Acesso a ofertas exclusivas
                        </div>
                    </div>
                </form>
        </div>
    </main>

    <script>
       window.onload = function () {
    const form = document.querySelector('#formCadastro');
    const password = document.getElementById('senha');
    const confirmPassword = document.getElementById('confirmSenha');
    const cpfField = document.getElementById('cpf');
    const celularField = document.getElementById('celular');
    const birthdate = document.querySelector('#birthdate');
    const birthdateError = document.querySelector('#birthdateError');

    // Máscara para CPF
    cpfField.addEventListener('input', function () {
        let value = cpfField.value.replace(/\D+/g, ''); // Remove caracteres não numéricos
        if (value.length > 3) value = value.substring(0, 3) + '.' + value.substring(3);
        if (value.length > 7) value = value.substring(0, 7) + '.' + value.substring(7);
        if (value.length > 11) value = value.substring(0, 11) + '-' + value.substring(11);
        cpfField.value = value.substring(0, 14); // Limita o tamanho
    });

    // Máscara para celular
    celularField.addEventListener('input', function () {
        let value = celularField.value.replace(/\D+/g, ''); // Remove caracteres não numéricos
        if (value.length > 2) value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
        if (value.length > 9) value = value.substring(0, 9) + '-' + value.substring(9);
        celularField.value = value.substring(0, 15); // Limita o tamanho
    });

    // Máscara para data de nascimento
    birthdate.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        if (value.length > 2) value = value.substring(0, 2) + '/' + value.substring(2);
        if (value.length > 5) value = value.substring(0, 5) + '/' + value.substring(5);
        birthdate.value = value.substring(0, 10); // Limita o tamanho
    });

    // Validação de data de nascimento
    function isValidDate(dateString) {
        const dateParts = dateString.split('/');
        if (dateParts.length !== 3) return false;

        const day = parseInt(dateParts[0], 10);
        const month = parseInt(dateParts[1], 10) - 1; // Meses no JS vão de 0 a 11
        const year = parseInt(dateParts[2], 10);

        if (isNaN(day) || isNaN(month) || isNaN(year)) return false;

        const date = new Date(year, month, day);
        return (
            date.getFullYear() === year &&
            date.getMonth() === month &&
            date.getDate() === day
        );
    }

    // Evento no envio do formulário
    form.onsubmit = function (event) {
        // Validação das senhas
        if (!password.value || !confirmPassword.value || password.value !== confirmPassword.value) {
            alert('As senhas não coincidem ou estão em branco.');
            event.preventDefault();
            return false;
        }

        // Validação da data de nascimento
        if (!isValidDate(birthdate.value)) {
            birthdateError.style.display = 'block'; // Exibe o erro
            event.preventDefault();
            return false;
        }

        // Garante que o CPF e celular estejam formatados
        cpfField.value = cpfField.value.replace(/\D+/g, '').replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        celularField.value = celularField.value.replace(/\D+/g, '').replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');

        alert('Formulário enviado com sucesso!');
    };
};


        

    </script>

   
</body>

</html>