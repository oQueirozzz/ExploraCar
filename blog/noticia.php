<?php
require_once("arrays.php");
$index = $_GET["index"];
$noticia = $noticias[$index];

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
function verificarLogin($email, $senha)
{
    $arquivo = "../loc/form/usuarios/usuarios.txt"; // Caminho do arquivo

    if (!file_exists($arquivo)) {
        echo "Erro: Arquivo de usuários não encontrado.<br>";
        return false;
    }

    $handle = fopen($arquivo, 'r'); // Abre o arquivo
    if (!$handle) {
        echo "Erro: Não foi possível abrir o arquivo.<br>";
        return false;
    }

    // Lê linha por linha
    while (($linha = fgets($handle)) !== false) {
        // echo "Lendo linha: $linha<br>"; // Debug

        // Remove espaços e quebras de linha
        $dados = explode(",", trim($linha));

        // Verifica se o número de campos está correto
        if (count($dados) < 8) {
            // echo "Erro: Linha com dados incompletos.<br>";
            continue;
        }

        list($cpf, $nome, $sobrenome, $emailArquivo, $pais, $dataNascimento, $telefone, $senhaArquivo) = $dados;

        // Exibe os dados para debug
        // echo "Email no arquivo: $emailArquivo, Senha no arquivo: $senhaArquivo<br>";

        // Verifica o email e a senha
        if ($email === $emailArquivo && $senha === $senhaArquivo) {
            fclose($handle);

            // Salva o nome completo na sessão
            $_SESSION['nome'] = $nome;
            $_SESSION['loggedin'] = true;  // Define que o usuário está logado
            $_SESSION['user_id'] = $cpf;  // Você pode armazenar o ID do usuário, se necessário
            return true;
        }
    }

    fclose($handle); // Fecha o arquivo
    return false; // Se não encontrou o usuário

    if ($email === $emailArquivo && $senha === $senhaArquivo) {
        fclose($handle);

        $_SESSION['nome'] = $nome ;
        echo "Usuário logado: " . $_SESSION['nome']; // Debug
        return true;
    }
}

// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Debug dos valores recebidos
    // var_dump($email, $senha);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (!verificarLogin($email, $senha)) {
        $erro = "Email ou senha incorretos.";
    }
}

// Após validar o login
// $_SESSION['loggedin'] = true; // Ou qualquer valor que identifique o usuário
// $_SESSION['user_id'] = $userId; // Opcional, caso precise identificar o usuário


// echo $mensagem;
// echo $erro;

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Redireciona com base no valor do botão clicado
    switch ($page) {
        case 'carros':
            header("Location: ../locação/veiculos.php");
            break;
        case 'sobre':
            header("Location: ../loc/sobrenos/sobre.php");
            break;
        case 'assinatura':
            header("Location: ../assinatura/assinatura.php");
            break;
        case 'blog':
            header("Location: ../blog/blog.php"); // Ajuste o caminho se necessário
            break;
        default:
            header("Location: index.php"); // Página padrão
            break;
    }
    exit; // Sempre encerre o script após header()
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../global/global.css">

    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f5f5f5;
            font-family: "Poppins", sans-serif ;
            justify-content: center;

        }
        .container-noticia{
            width: 60%;
            height: auto;
            margin-top: 150px;
        }
        .parteCima{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
            background-color: #ec821f;
            padding: 80px;
            height: 500px;
            margin-top: 50px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .container-noticia h1{
            text-align: center;
            font-size: 40px;
        }
        .container-noticia p{
            text-align: justify;
            margin-top: 100px;
            font-size: 18px;
        }
        .parteCima img{
            width: 60%;
            border-radius: 20px;
        }
        .container-noticia .data
    

    </style>
</head>
<body>
<header>
        <div class="cabecalho">
            <div id="menu-toggle" onclick="toggleMenu()">
                <i class="material-symbols-outlined ">menu</i>
            </div>

            <a href="index.php">
                <div class="logo"></div>
            </a>

            <form action="buscar.php" method="GET" class="barra-pesquisa">
                <input type="text" name="query" placeholder="Digite aqui..." required>
                <button type="submit">Pesquisar</button>
            </form>



            <div class="buttons">
                <div class="dropdown">
                    <?php if (isset($_SESSION['nome'])): ?>
                        <!-- Botão com o nome do usuário logado -->
                        <button id="principal-button" class="btn" onclick="toggleLogoutTab()">
                            <img src="../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="<?= htmlspecialchars($_SESSION['nome']); ?>"> </p>
                            <div class="seta"></div>
                        </button>

                        <!-- <button id="help-button" class="btn" onclick="toggleHelpTab()">
                            <img src="../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p>
                            <div class="seta"></div>
                        </button> -->




                    <?php else: ?>
                        <!-- Botão padrão "ENTRAR" -->

                        <button id="principal-button" class="btn" onclick="toggleInfoTab()">
                            <img src="../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="ENTRAR"> </p>
                            <div class="seta"></div>
                            <!-- <img id="seta" src="img/seta.png" alt=""> -->
                        </button>
                        </a>
                    <?php endif; ?>
                    <button id="help-button" class="btn" onclick="toggleHelpTab()">
                        <img src="../global/img/help.png" alt="">
                        <span></span>
                        <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p>
                        <div class="seta"></div>
                    </button>
                </div>

                <div id="logout-tab" class="logout-tab">
                    <div class="logout-content">
                        <span class="close-btn" onclick="toggleLogoutTab()">&times;</span>
                        <div class="buttons">
                            <a href="logout.php">
                                <button id="logout-button">
                                    <span></span>
                                    <p data-start="good luck!" data-text="start!" data-title="Sair"> </p>
                                    </button">
                            </a>
                        </div>
                        <span class="close-btn" onclick="toggleLogoutTab()">&times;</span>

                    </div>
                </div>

                <div id="help-tab" class="help-tab">
                    <div class="help-content">
                        <span class="close-btn" onclick="toggleHelpTab()">&times;</span>
                        <div class="buttons">
                            <a href="../loc/duvidasfrequentes/duvidas.php">
                                <button id="duvidas-button">
                                    <span></span>
                                    <p data-start="good luck!" data-text="start!" data-title="Central de Ajuda"> </p>
                                </button>
                            </a>
                        </div>
                        <div class="container-contact">
                            <div class="header-contact">
                                <span>Canais de atendimento</span>
                            </div>
                            <div class="contact-info">
                                <div>
                                    <i class="fas fa-phone"></i> Principais Capitais
                                    <div><strong>4003 7368</strong></div>
                                </div>
                                <!-- <div>
                                        <i class="fas fa-phone"></i> Demais Localidades
                                        <div><strong>0800 604 7368</strong></div>
                                    </div> -->
                                <div>
                                    <i class="fas fa-phone"></i> Ligações Internacionais
                                    <div><strong>+55 (41) 4042 1479</strong></div>
                                </div>
                            </div>
                            <div class="schedule">
                                <div class="schedule-header">Horarios de atendimento</div>
                                <table class="schedule-table">
                                    <tr>
                                        <td>Segunda-feira</td>
                                        <td>07:00 - 23:59</td>
                                    </tr>
                                    <tr>
                                        <td>Terça-feira</td>
                                        <td>07:00 - 23:59</td>
                                    </tr>
                                    <tr>
                                        <td>Quarta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr>
                                        <td>Quinta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr>
                                        <td>Sexta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr>
                                        <td>Sábado</td>
                                        <td>00:00 - 19:00</td>
                                    </tr>
                                    <tr>
                                        <td>Domingo</td>
                                        <td>10:00 - 19:00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>





                    </div>
                </div>


                <!-- Aba de informações que será exibida no meio da página -->
                <div id="info-tab" class="info-tab">
                    <div class="info-content">
                        <?php if ($mostrarFormulario === 'login'): ?>
                            <span class="close-btn" onclick="toggleInfoTab()">&times;</span>
                            <div class="register-section">
                                <h2>Cadastre-se</h2>
                                <button class="btn" onclick="window.location.href='../loc/form/form.php'"><span></span>Criar
                                    Nova
                                    Conta</button>
                                <ul>
                                    <li>✅ Rápido e fácil reservar</li>
                                    <li>✅ Descontos de até 30%</li>
                                    <li>✅ Acesso a ofertas exclusivas</li>
                                    <li>✅ Ganhe cashback</li>
                                </ul>
                            </div>
                            <div class="login-section">
                                <h2>Acesse sua Conta</h2>
                                <form action="" method="post">
                                    <input type="hidden" name="acao" value="login"> <!-- Ação para diferenciar o login -->
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" required>

                                    <label for="senha">Senha</label>
                                    <input type="password" id="password" name="senha" required>

                                    <a href="../esqueceusenha/esqueceusenha.php" class="esqueci">Esqueci minha senha</a>

                                    <button type="submit" name="acao" value="login" class="login-button"><span></span>Entrar</button>
                                </form>
                                <?php if (!empty($erro)): ?>
                                    <p class="erro"><?= htmlspecialchars($erro) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <nav id="sidebar">
            <ul class="menu">
            <form action="" method="get">
    <li>
        <button type="submit" name="page" value="carros">
            <img src="../global/img/carroICON.jpg" alt="veiculos" id="transparent">
            <span>Carros</span>
        </button>
    </li>
    <li>
        <button type="submit" name="page" value="sobre">
            <img src="../global/img/sobre.png" alt="Sobre">
            <span>Sobre nós</span>
        </button>
    </li>
    <hr>
    <li>
        <button type="submit" name="page" value="assinatura">
            <img src="../global/img/assinatura.png" alt="Pacotes">
            <span>Pacotes</span>
        </button>
    </li>
    <li>
        <button type="submit" name="page" value="blog">
            <img src="../global/img/blog.png" id="blog" alt="Blog">
            <span>Blog</span>
        </button>
    </li>
    <hr>
</form>

            </ul>
        </nav>
    </header>

<?php
$index = $_GET["index"];
$noticia = $noticias[$index];

print
 "<div class='container-noticia'>
   <div class='parteCima'>
       <h1>" . $noticia["nome"] . "</h1>
        <img src='imgs-blog/" . $noticia["imagem"] . "'>
   </div>
    <p>" . $noticia["main"] . "</p>
    <p>" . $noticia["data"] . "</p>
</div>"
?>
    
</body>
</html>
