<?php
session_start();  // Inicia a sessão, permitindo que dados sejam armazenados durante a navegação (como o nome do usuário)


// Desativar exibição de erros
error_reporting(0);  // Desativa a exibição de erros PHP, não mostra avisos ou erros na tela
ini_set('display_errors', 0);  // Define a configuração do PHP para não exibir erros

$erro = '';  // Inicializa a variável de erro, usada para armazenar mensagens de erro
$mensagem = '';  // Inicializa a variável de mensagem, usada para mensagens informativas
$mostrarFormulario = 'login'; // Define que o formulário inicial a ser exibido será o de login

// Detecta se o usuário clicou em "Esqueci a senha" e muda o formulário a ser exibido
if (isset($_GET['acao']) && $_GET['acao'] === 'esqueceu_senha') {
    $mostrarFormulario = 'esqueceuSenha';  // Se a URL contiver 'acao=esqueceu_senha', troca o formulário para o de recuperação de senha
}

// Função para verificar login
function verificarLogin($email, $senha)
{
    $arquivo = "../loc/form/usuarios/usuarios.txt"; // Caminho do arquivo onde os usuários estão armazenados

    if (!file_exists($arquivo)) {  // Verifica se o arquivo de usuários existe
        echo "Erro: Arquivo de usuários não encontrado.<br>";  // Exibe mensagem de erro caso o arquivo não exista
        return false;  // Retorna falso, pois o arquivo não foi encontrado
    }

    $handle = fopen($arquivo, 'r'); // Abre o arquivo para leitura
    if (!$handle) {  // Verifica se o arquivo foi aberto corretamente
        echo "Erro: Não foi possível abrir o arquivo.<br>";  // Exibe mensagem de erro se o arquivo não puder ser aberto
        return false;  // Retorna falso, pois o arquivo não foi aberto
    }

    // Lê linha por linha do arquivo
    while (($linha = fgets($handle)) !== false) {  // Lê cada linha do arquivo até o final
        // echo "Lendo linha: $linha<br>"; // Debug (comentado)

        // Remove espaços em branco e quebras de linha, separa os dados por vírgula
        $dados = explode(",", trim($linha));

        // Verifica se o número de campos está correto (deve ter pelo menos 8 campos)
        if (count($dados) < 8) {
            // echo "Erro: Linha com dados incompletos.<br>"; // Debug (comentado)
            continue;  // Se a linha estiver incompleta, pula para a próxima
        }

        // Atribui as variáveis a partir da linha do arquivo (descompacta os dados)
        list($cpf, $nome, $sobrenome, $emailArquivo, $pais, $dataNascimento, $telefone, $senhaArquivo) = $dados;

        // Exibe os dados para debug (comentado)
        // echo "Email no arquivo: $emailArquivo, Senha no arquivo: $senhaArquivo<br>";

        // Verifica se o email e a senha correspondem ao que foi enviado pelo usuário
        if ($email === $emailArquivo && $senha === $senhaArquivo) {
            fclose($handle);  // Fecha o arquivo após encontrar uma correspondência

            // Salva os dados do usuário na sessão para futuras referências
            $_SESSION['nome'] = $nome;  // Salva o nome do usuário
            $_SESSION['loggedin'] = true;  // Define que o usuário está logado
            $_SESSION['user_id'] = $cpf;  // Armazena o CPF do usuário como um identificador único
            return true;  // Retorna verdadeiro, indicando que o login foi bem-sucedido
        }
    }

    fclose($handle);  // Fecha o arquivo após terminar a leitura
    return false;  // Retorna falso caso não tenha encontrado um usuário com o email e senha fornecidos
}

// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email']);  // Obtém e limpa o valor do email informado pelo usuário
    $senha = trim($_POST['senha']);  // Obtém e limpa o valor da senha informada pelo usuário

    // Debug dos valores recebidos (comentado)
    // var_dump($email, $senha);

    // Verifica se os campos de email e senha estão preenchidos
    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";  // Mensagem de erro se algum campo estiver vazio
    } elseif (!verificarLogin($email, $senha)) {  // Chama a função de verificação de login
        $erro = "Email ou senha incorretos.";  // Mensagem de erro caso o login falhe
    }
}

// Após validar o login
// $_SESSION['loggedin'] = true; // Ou qualquer valor que identifique o usuário
// $_SESSION['user_id'] = $userId; // Opcional, caso precise identificar o usuário


// echo $mensagem;
// echo $erro;

if (isset($_GET['page'])) {  // Verifica se existe o parâmetro 'page' na URL
    $page = $_GET['page'];  // Obtém o valor do parâmetro 'page'

    // Redireciona com base no valor do parâmetro 'page'
    switch ($page) {  // Compara o valor de 'page' e realiza a ação correspondente
        case 'carros':  // Se for 'carros', redireciona para a página de veículos
            header("Location: ../locação/veiculos.php");
            break;
        case 'sobre':  // Se for 'sobre', redireciona para a página sobre
            header("Location: ../loc/sobrenos/sobre.php");
            break;
        case 'assinatura':  // Se for 'assinatura', redireciona para a página de assinatura
            header("Location: ../assinatura/assinatura.php");
            break;
        case 'blog':  // Se for 'blog', redireciona para a página do blog
            header("Location: ../blog/blog.php"); // Ajuste o caminho se necessário
            break;
        default:  // Caso o valor de 'page' não corresponda a nenhum dos casos, redireciona para a página inicial
            header("Location: index.php"); // Página padrão
            break;
    }
    exit;  // Sempre encerre o script após redirecionar
}

?>

<!DOCTYPE html> <!-- Declara o tipo do documento, especificando que é um documento HTML5 -->
<html lang="pt-BR"> <!-- Inicia o documento HTML e define o idioma como português do Brasil -->

<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8, que suporta caracteres especiais, como acentos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajusta a escala da página para dispositivos móveis, garantindo que ela seja responsiva -->
    <title>Aluguel de Carros - ExploraCar</title> <!-- Define o título da página que será exibido na aba do navegador -->
    <link rel="stylesheet" href="@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Oswald:wght@200..700&display=swap');"> <!-- Importa fontes do Google Fonts para estilizar o texto da página -->
    <link rel="stylesheet" href="../global/global.css"> <!-- Importa um arquivo CSS global para estilos gerais da página -->
    <link rel="stylesheet" href="veiculos.css"> <!-- Importa um arquivo CSS específico para a página de veículos -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" /> <!-- Importa ícones de fontes Material Symbols para ícones gráficos na página -->
</head>

<body>
    <header>
        <div class="cabecalho"> <!-- Define a estrutura principal do cabeçalho da página -->
            <div id="menu-toggle" onclick="toggleMenu()"> <!-- Cria um botão que, ao ser clicado, chama a função 'toggleMenu()' para alternar o menu -->
                <i class="material-symbols-outlined ">menu</i> <!-- Exibe um ícone de menu usando as fontes Material Symbols -->
            </div>

            <a href="../home/index.php">
                <div class="logo"></div> <!-- Cria uma área para o logotipo, que é um link para a página inicial -->
            </a>

            <form action="buscar.php" method="GET" class="barra-pesquisa"> <!-- Cria um formulário de pesquisa com método GET, que envia os dados para 'buscar.php' -->
                <input type="text" name="query" placeholder="Digite aqui..." required> <!-- Campo de entrada de texto para o usuário digitar a pesquisa, o atributo 'required' exige que o campo seja preenchido -->
                <button type="submit">Pesquisar</button> <!-- Botão para submeter o formulário de pesquisa -->
            </form>

            <div class="buttons"> <!-- Início de uma seção de botões (ainda não foi finalizada no trecho do código fornecido) -->
                <div class="dropdown"> <!-- Inicia um menu suspenso (dropdown), que provavelmente vai ser usado para exibir opções adicionais (não completo) -->
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

                        <button id="principal-button" class="btn" onclick="toggleInfoTab()"> <!-- Cria um botão com ID 'principal-button' e classe 'btn', que chama a função 'toggleInfoTab()' quando clicado -->
                            <img src="../global/img/file.png" alt=""> <!-- Exibe uma imagem de um arquivo (ícone) dentro do botão -->
                            <span></span> <!-- Um elemento vazio de 'span', provavelmente utilizado para aplicar algum estilo ou efeitos visuais -->
                            <p data-start="good luck!" data-text="start!" data-title="ENTRAR"> </p> <!-- Um parágrafo com atributos personalizados que podem ser utilizados para animações ou interatividade -->
                            <div class="seta"></div> <!-- Um elemento 'div' com a classe 'seta', provavelmente usado para desenhar ou exibir uma seta indicativa -->
                            <!-- <img id="seta" src="img/seta.png" alt=""> --> <!-- Linha comentada, talvez uma imagem de seta que estava sendo usada anteriormente -->
                        </button> <!-- Fecha o botão principal -->
                        </a> <!-- Fechamento de uma tag de link (não está claro de onde vem) -->
                    <?php endif; ?> <!-- Fim de uma condição PHP, provavelmente após um 'if' que decide se esse botão será exibido -->
                    <button id="help-button" class="btn" onclick="toggleHelpTab()"> <!-- Cria um botão de ajuda, com ID 'help-button', que chama a função 'toggleHelpTab()' -->
                        <img src="../global/img/help.png" alt=""> <!-- Exibe um ícone de ajuda dentro do botão -->
                        <span></span> <!-- Um elemento 'span' vazio, possivelmente utilizado para efeitos visuais ou animações -->
                        <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p> <!-- Um parágrafo com atributos personalizados para efeitos -->
                        <div class="seta"></div> <!-- Um elemento 'div' com a classe 'seta', provavelmente usado para exibir uma seta -->
                    </button> <!-- Fecha o botão de ajuda -->
                </div> <!-- Fecha o contêiner de botões -->

                <!-- Início da aba de logout -->
                <div id="logout-tab" class="logout-tab"> <!-- Define a aba de logout com ID 'logout-tab' e a classe 'logout-tab' -->
                    <div class="logout-content"> <!-- Contêiner principal da aba de logout -->
                        <span class="close-btn" onclick="toggleLogoutTab()">&times;</span> <!-- Um botão de fechar (ícone '×') que chama a função 'toggleLogoutTab()' ao ser clicado -->
                        <div class="buttons"> <!-- Início de um contêiner para botões dentro da aba de logout -->
                            <a href="logout.php"> <!-- Cria um link para 'logout.php', provavelmente para realizar o logout -->
                                <button id="logout-button"> <!-- Cria um botão de logout com ID 'logout-button' -->
                                    <span></span> <!-- Um elemento 'span' vazio, utilizado para efeitos visuais ou animações -->
                                    <p data-start="good luck!" data-text="start!" data-title="Sair"> </p> <!-- Um parágrafo com atributos personalizados para animações ou interatividade -->
                                </button> <!-- Fecha o botão de logout -->
                            </a> <!-- Fecha o link para logout -->
                        </div> <!-- Fecha o contêiner de botões -->
                        <span class="close-btn" onclick="toggleLogoutTab()">&times;</span> <!-- Outro ícone de fechar (ícone '×'), para fechar a aba de logout -->
                    </div> <!-- Fecha o contêiner principal de logout -->
                </div> <!-- Fecha a aba de logout -->

                <!-- Início da aba de ajuda -->
                <div id="help-tab" class="help-tab"> <!-- Define a aba de ajuda com ID 'help-tab' e classe 'help-tab' -->
                    <div class="help-content"> <!-- Contêiner principal da aba de ajuda -->
                        <span class="close-btn" onclick="toggleHelpTab()">&times;</span> <!-- Botão de fechar (ícone '×') que chama a função 'toggleHelpTab()' ao ser clicado -->
                        <div class="buttons"> <!-- Contêiner para os botões dentro da aba de ajuda -->
                            <a href="../loc/duvidasfrequentes/duvidas.php"> <!-- Link para a página de dúvidas frequentes -->
                                <button id="duvidas-button"> <!-- Botão para acessar a página de dúvidas frequentes -->
                                    <span></span> <!-- Elemento 'span' vazio, utilizado para efeitos visuais -->
                                    <p data-start="good luck!" data-text="start!" data-title="Central de Ajuda"> </p> <!-- Parágrafo com atributos personalizados -->
                                </button> <!-- Fecha o botão de dúvidas frequentes -->
                            </a> <!-- Fecha o link para dúvidas frequentes -->
                        </div> <!-- Fecha o contêiner de botões -->
                        <div class="container-contact"> <!-- Contêiner que agrupa informações de contato -->
                            <div class="header-contact"> <!-- Cabeçalho do contêiner de contato -->
                                <span>Canais de atendimento</span> <!-- Título 'Canais de atendimento' -->
                            </div> <!-- Fecha o cabeçalho de contato -->
                            <div class="contact-info"> <!-- Contêiner com informações de contato -->
                                <div> <!-- Informações de contato -->
                                    <i class="fas fa-phone"></i> Principais Capitais <!-- Ícone de telefone seguido do texto 'Principais Capitais' -->
                                    <div><strong>4003 7368</strong></div> <!-- Número de telefone para as principais capitais -->
                                </div> <!-- Fecha o item de contato -->
                                <!-- <div>
                        <i class="fas fa-phone"></i> Demais Localidades
                        <div><strong>0800 604 7368</strong></div>
                    </div> -->
                                <div> <!-- Outra informação de contato -->
                                    <i class="fas fa-phone"></i> Ligações Internacionais <!-- Ícone de telefone seguido do texto 'Ligações Internacionais' -->
                                    <div><strong>+55 (41) 4042 1479</strong></div> <!-- Número de telefone para ligações internacionais -->
                                </div> <!-- Fecha o item de contato -->
                            </div> <!-- Fecha o contêiner de informações de contato -->
                            <div class="schedule"> <!-- Contêiner para os horários de atendimento -->
                                <div class="schedule-header">Horarios de atendimento</div> <!-- Cabeçalho 'Horários de atendimento' -->
                                <table class="schedule-table"> <!-- Tabela com os horários de atendimento -->
                                    <tr> <!-- Linha de horário -->
                                        <td>Segunda-feira</td> <!-- Dia da semana -->
                                        <td>07:00 - 23:59</td> <!-- Horário de atendimento -->
                                    </tr> <!-- Fecha a linha -->
                                    <tr> <!-- Outra linha de horário -->
                                        <td>Terça-feira</td>
                                        <td>07:00 - 23:59</td>
                                    </tr>
                                    <tr> <!-- Linha para quarta-feira -->
                                        <td>Quarta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr> <!-- Linha para quinta-feira -->
                                        <td>Quinta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr> <!-- Linha para sexta-feira -->
                                        <td>Sexta-feira</td>
                                        <td>00:00 - 23:59</td>
                                    </tr>
                                    <tr> <!-- Linha para sábado -->
                                        <td>Sábado</td>
                                        <td>00:00 - 19:00</td>
                                    </tr>
                                    <tr> <!-- Linha para domingo -->
                                        <td>Domingo</td>
                                        <td>10:00 - 19:00</td>
                                    </tr>
                                </table> <!-- Fecha a tabela de horários -->
                            </div> <!-- Fecha o contêiner de horários -->
                        </div> <!-- Fecha o contêiner de contato -->
                    </div> <!-- Fecha o conteúdo da aba de ajuda -->
                </div> <!-- Fecha a aba de ajuda -->

                <!-- Aba de informações que será exibida no meio da página -->
                <div id="info-tab" class="info-tab">  <!-- Define um contêiner para uma aba de informações com ID 'info-tab' e a classe 'info-tab' -->
    <div class="info-content">  <!-- Contêiner principal da aba de informações -->
        <?php if ($mostrarFormulario === 'login'): ?>  <!-- Verifica se a variável $mostrarFormulario é igual a 'login' para exibir o formulário de login -->
            <span class="close-btn" onclick="toggleInfoTab()">&times;</span>  <!-- Um ícone de fechar (×) que chama a função 'toggleInfoTab()' ao ser clicado, para fechar a aba -->
            <div class="register-section">  <!-- Seção de cadastro -->
                <h2>Cadastre-se</h2>  <!-- Título da seção de cadastro -->
                <button class="btn" onclick="window.location.href='../loc/form/form.php'"><span></span>Criar Nova Conta</button>  <!-- Botão para redirecionar para a página de cadastro -->
                <ul>  <!-- Lista de benefícios ao se cadastrar -->
                    <li>✅ Rápido e fácil reservar</li>  <!-- Benefício de ser rápido e fácil -->
                    <li>✅ Descontos de até 30%</li>  <!-- Benefício de descontos -->
                    <li>✅ Acesso a ofertas exclusivas</li>  <!-- Benefício de ofertas exclusivas -->
                    <li>✅ Ganhe cashback</li>  <!-- Benefício de cashback -->
                </ul>  <!-- Fecha a lista de benefícios -->
            </div>  <!-- Fecha a seção de cadastro -->

            <div class="login-section">  <!-- Seção de login -->
                <h2>Acesse sua Conta</h2>  <!-- Título da seção de login -->
                <form action="" method="post">  <!-- Formulário para login -->
                    <input type="hidden" name="acao" value="login"> <!-- Campo oculto para indicar que é uma ação de login -->
                    <label for="email">Email</label>  <!-- Rótulo para o campo de email -->
                    <input type="email" id="email" name="email" required>  <!-- Campo de entrada de email com validação de campo obrigatório -->
                    
                    <label for="senha">Senha</label>  <!-- Rótulo para o campo de senha -->
                    <input type="password" id="password" name="senha" required>  <!-- Campo de entrada de senha com validação de campo obrigatório -->

                    <a href="../esqueceusenha/esqueceusenha.php" class="esqueci">Esqueci minha senha</a>  <!-- Link para recuperação de senha -->

                    <button type="submit" name="acao" value="login" class="login-button"><span></span>Entrar</button>  <!-- Botão para submeter o formulário de login -->
                </form>

                <?php if (!empty($erro)): ?>  <!-- Se houver uma mensagem de erro, exibe-a -->
                    <p class="erro"><?= htmlspecialchars($erro) ?></p>  <!-- Exibe o erro de login, escapando caracteres especiais para evitar XSS -->
                <?php endif; ?>  <!-- Fim da verificação de erro -->
            </div>  <!-- Fecha a seção de login -->
        <?php endif; ?>  <!-- Fim da verificação do formulário de login -->
    </div>  <!-- Fecha o contêiner principal da aba de informações -->
</div>  <!-- Fecha a aba de informações -->

</div>  <!-- Parece ser o fechamento de um contêiner maior (não mostrado aqui) -->

<!-- Barra lateral de navegação -->
<nav id="sidebar">  <!-- Define a barra lateral de navegação com o ID 'sidebar' -->
    <ul class="menu">  <!-- Lista de itens de menu -->
        <form action="" method="get">  <!-- Formulário de envio via método GET, provavelmente para navegar entre páginas -->

            <li>  <!-- Item do menu 'Carros' -->
                <button type="submit" name="page" value="carros">  <!-- Botão para enviar o valor 'carros' via GET -->
                    <img src="../global/img/carroICON.jpg" alt="veiculos" id="transparent">  <!-- Ícone de carro, imagem relacionada à categoria 'carros' -->
                    <span>Carros</span>  <!-- Texto do item de menu -->
                </button>  <!-- Fecha o botão -->
            </li>  <!-- Fecha o item 'Carros' -->

            <li>  <!-- Item do menu 'Sobre nós' -->
                <button type="submit" name="page" value="sobre">  <!-- Botão para enviar o valor 'sobre' via GET -->
                    <img src="../global/img/sobre.png" alt="Sobre">  <!-- Ícone de sobre -->
                    <span>Sobre nós</span>  <!-- Texto do item de menu -->
                </button>  <!-- Fecha o botão -->
            </li>  <!-- Fecha o item 'Sobre nós' -->
            
            <hr>  <!-- Linha horizontal separadora -->

            <li>  <!-- Item do menu 'Pacotes' -->
                <button type="submit" name="page" value="assinatura">  <!-- Botão para enviar o valor 'assinatura' via GET -->
                    <img src="../global/img/assinatura.png" alt="Pacotes">  <!-- Ícone de pacotes -->
                    <span>Pacotes</span>  <!-- Texto do item de menu -->
                </button>  <!-- Fecha o botão -->
            </li>  <!-- Fecha o item 'Pacotes' -->

            <li>  <!-- Item do menu 'Blog' -->
                <button type="submit" name="page" value="blog">  <!-- Botão para enviar o valor 'blog' via GET -->
                    <img src="../global/img/blog.png" id="blog" alt="Blog">  <!-- Ícone de blog -->
                    <span>Blog</span>  <!-- Texto do item de menu -->
                </button>  <!-- Fecha o botão -->
            </li>  <!-- Fecha o item 'Blog' -->

            <hr>  <!-- Linha horizontal separadora -->
        </form>  <!-- Fecha o formulário de navegação -->
    </ul>  <!-- Fecha a lista de itens do menu -->
</nav>  <!-- Fecha a barra lateral de navegação -->

</header>  <!-- Fecha o cabeçalho da página -->
    <main>
        <!-- Esse código gera botões de marcas -->
        <div class="button-container">
            <button class="brand-button" onclick="addCards(10, 'Toyota', this)">
                <img src="./img/img Logos/Toyota Logo.png" alt="Toyota Logo">
                Toyota
            </button>
            <button class="brand-button" onclick="addCards(10, 'Ford', this)">
                <img src="./img/img Logos/Ford Logo.png" alt="Ford Logo">
                Ford
            </button>
            <button class="brand-button" onclick="addCards(10, 'Chevrolet', this)">
                <img src="./img/img Logos/Chevrolet Logo.png" alt="Chevrolet Logo">
                Chevrolet
            </button>
            <button class="brand-button" onclick="addCards(10, 'Volkswagen', this)">
                <img src="./img/img Logos/Volkswagen Logo.png" alt="Volkswagen Logo">
                Volkswagen
            </button>
            <button class="brand-button" onclick="addCards(10, 'Fiat', this)">
                <img src="./img/img Logos/Fiat Logo.png" alt="Fiat Logo">
                Fiat
            </button>
            <button class="brand-button" onclick="addCards(10, 'Honda', this)">
                <img src="./img/img Logos/Honda Logo.png" alt="Honda Logo">
                Honda
            </button>
            <button class="brand-button" onclick="addCards(10, 'Hyundai', this)">
                <img src="./img/img Logos/Hyundai Logo.png" alt="Hyundai Logo">
                Hyundai
            </button>
            <button class="brand-button" onclick="addCards(10, 'Renault', this)">
                <img src="./img/img Logos/Renault Logo.png" alt="Renault Logo">
                Renault
            </button>
            <button class="brand-button" onclick="addCards(10, 'Nissan', this)">
                <img src="./img/img Logos/Nissan Logo.png" alt="Nissan Logo">
                Nissan
            </button>
            <button class="brand-button" onclick="addCards(10, 'Peugeot', this)">
                <img src="./img/img Logos/Peugeot Logo.png" alt="Peugeot Logo">
                Peugeot
            </button>
            <button class="brand-button" onclick="addCards(10, 'Citroën', this)">
                <img src="./img/img Logos/Citroën Logo.png" alt="Citroën Logo">
                Citroën
            </button>
            <button class="brand-button" onclick="addCards(10, 'BlackFriday', this)">
                <img src="./img/img Logos/Black Friday Logo.png" alt="Audi Logo">
                Black Friday
            </button>
        </div>
        <div id="card-container">
        </div>
        <script>
            // Esse aqui é um array que monta os cards dos carros com titulo, imagem, descrição e preço...
            const vehicles = {
                'Toyota': [{
                        title: 'Toyota Corolla',
                        img: './img/img Carros/img Toyota/Corolla.png',
                        desc: 'O Toyota Corolla é um sedã renomado pela confiabilidade, conforto e eficiência. Com design moderno e opções de motorização híbrida e a gasolina, oferece uma condução suave, baixo consumo de combustível e uma cabine bem equipada. Ideal para quem busca um carro familiar ou de uso diário com segurança e sofisticação.',
                        descontPrice: 'de R$ 1.750,00 por ',
                        price: 'R$ 1.050,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.8 Flex',
                        cambio: 'Manual de 6 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Corolla 2.jpg',
                        img3: './img/img Interior/Toyota/Toyota Corolla 3.webp',
                        img4: './img/img Interior/Toyota/Toyota Corolla 4.jpg'
                    },
                    {
                        title: 'Toyota Yaris Hatch',
                        img: './img/img Carros/img Toyota/Yaris Hatch.png',
                        desc: 'Compacto e ágil, o Toyota Yaris Hatch combina um design esportivo com um motor eficiente. Oferece ótimo desempenho urbano, boa conectividade e um interior funcional. É a escolha perfeita para quem precisa de um carro prático e econômico para o dia a dia.',
                        descontPrice: 'de R$ 1.260,00 por ',
                        price: 'R$ 840,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Yaris Hatch 2.webp',
                        img3: './img/img Interior/Toyota/Toyota Yaris Hatch 3.jpg',
                        img4: './img/img Interior/Toyota/Toyota Yaris Hatch 4.webp'
                    },
                    {
                        title: 'Toyota Yaris Sedã',
                        img: './img/img Carros/img Toyota/Yaris Sedã.png',
                        desc: 'O Yaris Sedã traz o conforto e a eficiência do hatch em um formato mais espaçoso. Com bom desempenho e baixo consumo de combustível, é uma opção acessível e confortável para quem busca um sedã compacto com tecnologias de segurança e conectividade.',
                        descontPrice: 'de R$ 1.260,00 por ',
                        price: 'R$ 840,00 ',
                        diaria: '',
                        doors: '4',
                        engine: '1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Yaris Sedã 2.webp',
                        img3: './img/img Interior/Toyota/Toyota Yaris Sedã 3.jpg',
                        img4: './img/img Interior/Toyota/Toyota Yaris Sedã 4.webp'
                    },
                    {
                        title: 'Toyota Corolla Cross',
                        img: './img/img Carros/img Toyota/Corolla Cross.png',
                        desc: 'O Corolla Cross é o SUV da linha Corolla, oferecendo design sofisticado, motorização híbrida e a gasolina, além de um interior espaçoso e moderno. Com tração integral disponível, é ideal para quem busca um SUV versátil e eficiente para uso urbano e aventuras leves.',
                        descontPrice: 'de R$ 2.100,00 por ',
                        price: 'R$ 1.400,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.0 Flex',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Corolla Cross 2.jpeg',
                        img3: './img/img Interior/Toyota/Toyota Corolla Cross 3.webp',
                        img4: './img/img Interior/Toyota/Toyota Corolla Cross 4.jpeg'
                    },
                    {
                        title: 'Toyota SW4',
                        img: './img/img Carros/img Toyota/SW4.png',
                        desc: 'O Toyota SW4 é um SUV robusto, com tração 4x4 e excelente desempenho off-road. Baseado na Hilux, é perfeito para quem precisa de um veículo para terrenos difíceis, com conforto, espaço e recursos de tecnologia avançada para toda a família.',
                        descontPrice: 'de R$ 4.900,00 por ',
                        price: 'R$ 2.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.8 Turbo Diesel',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota SW4 2.webp',
                        img3: './img/img Interior/Toyota/Toyota SW4 3.jpeg',
                        img4: './img/img Interior/Toyota/Toyota SW4 4.jpeg'
                    },
                    {
                        title: 'Toyota Hilux',
                        img: './img/img Carros/img Toyota/Toyota Hilux.png',
                        desc: 'A Toyota Hilux é uma picape robusta e versátil, conhecida pela sua durabilidade e desempenho em terrenos difíceis. Ideal para trabalho pesado e aventuras off-road, oferece conforto na cabine e recursos tecnológicos modernos.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.8 Turbo Diesel',
                        cambio: 'Manual de 6 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Hilux 2.jpg',
                        img3: './img/img Interior/Toyota/Toyota Hilux 3.jpg',
                        img4: './img/img Interior/Toyota/Toyota Hilux 4.webp'
                    },
                    {
                        title: 'Toyota Camry',
                        img: './img/img Carros/img Toyota/Toyota Camry.png',
                        desc: 'O Toyota Camry é um sedã médio de luxo, com design elegante e interior confortável. Famoso pela eficiência de combustível, oferece uma condução suave e recursos avançados, sendo ideal para quem busca um carro refinado e econômico.',
                        descontPrice: 'de R$ 2.200,00 por ',
                        price: 'R$ 1.600,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.5 Flex ou 2.5 Híbrido',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Camry 2.jpg',
                        img3: './img/img Interior/Toyota/Toyota Camry 3.webp',
                        img4: './img/img Interior/Toyota/Toyota Camry 4.jpg'
                    },
                    {
                        title: 'Toyota Prius',
                        img: './img/img Carros/img Toyota/Toyota Prius.png',
                        desc: 'O Toyota Prius é um híbrido eficiente e sustentável, com design aerodinâmico e motor que combina gasolina e eletricidade. Oferece excelente economia de combustível e baixo impacto ambiental, ideal para quem busca um veículo ecológico e econômico.',
                        descontPrice: 'de R$ 1.600,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.8 Híbrido',
                        cambio: 'CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Prius 2.jpg',
                        img3: './img/img Interior/Toyota/Toyota Prius 3.webp',
                        img4: './img/img Interior/Toyota/Toyota Prius 4.jpg'
                    },
                    {
                        title: 'Toyota RAV4',
                        img: './img/img Carros/img Toyota/Toyota RAV4.png',
                        desc: 'O Toyota RAV4 é um SUV compacto, versátil e confortável, com bom desempenho tanto na cidade quanto em viagens. Disponível em versão híbrida, é econômico e oferece recursos de segurança e conectividade para o dia a dia.',
                        descontPrice: 'de R$ 2.000,00 por ',
                        price: 'R$ 1.400,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.5 Flex ou 2.5 Híbrido',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota RAV4 2.webp',
                        img3: './img/img Interior/Toyota/Toyota RAV4 3.png',
                        img4: './img/img Interior/Toyota/Toyota RAV4 4.jpeg'
                    },
                    {
                        title: 'Toyota Land Cruiser',
                        img: './img/img Carros/img Toyota/Toyota Land Cruiser.png',
                        desc: 'O Toyota Land Cruiser é um SUV robusto e poderoso, projetado para enfrentar os terrenos mais difíceis. Com interior luxuoso e excelente desempenho off-road, é perfeito para aventuras e expedições exigentes.',
                        descontPrice: 'de R$ 4.500,00 por ',
                        price: 'R$ 3.000,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '4.5 V8 Turbo Diesel',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Toyota/Toyota Land Cruiser 2.webp',
                        img3: './img/img Interior/Toyota/Toyota Land Cruiser 3.jpg',
                        img4: './img/img Interior/Toyota/Toyota Land Cruiser 4.webp'
                    },
                ],
                'Ford': [{
                        title: 'Ford Bronco Sport',
                        img: './img/img Carros/img Ford/FordBroncoSport.png',
                        desc: 'O Ford Bronco Sport é um SUV compacto projetado para aventuras off-road, com tração nas quatro rodas e modos de condução. Seu interior é espaçoso e tecnológico, equilibrando desempenho em trilhas e conforto urbano.',
                        descontPrice: 'de R$  3.500,00 por ',
                        price: 'R$ 2.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Turbo',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Bronco Sport 2.webp',
                        img3: './img/img Interior/Ford/Ford Bronco Sport 3.avif',
                        img4: './img/img Interior/Ford/Ford Bronco Sport 4.webp'
                    },
                    {
                        title: 'Ford Eco Sport',
                        img: './img/img Carros/img Ford/FordEcoSport.png',
                        desc: 'O Ford EcoSport é um SUV compacto versátil, ideal para a cidade. Com design moderno e boa altura do solo, oferece conforto interno e espaço para carga. Equipado com tecnologia avançada, é uma opção prática e funcional para o dia a dia.',
                        descontPrice: 'de R$ 1.400 ,00 por ',
                        price: 'R$ 840,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Flex ou 2.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Eco Sport 2.jpg',
                        img3: './img/img Interior/Ford/Ford Eco Sport 3.jpg',
                        img4: './img/img Interior/Ford/Ford Eco Sport 4.webp'
                    },
                    {
                        title: 'Ford Fiesta',
                        img: './img/img Carros/img Ford/FordFiesta.png',
                        desc: 'O Ford Fiesta é um hatchback compacto ágil e estiloso. Conhecido pela eficiência e diversão ao dirigir, oferece conforto interno e tecnologias modernas, sendo uma opção prática para o dia a dia.',
                        descontPrice: 'de R$ 1.260,00 por ',
                        price: 'R$  700,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Fiesta 2.jpg',
                        img3: './img/img Interior/Ford/Ford Fiesta 3.jpg',
                        img4: './img/img Interior/Ford/Ford Fiesta 4.webp'
                    },
                    {
                        title: 'Ford Fusion',
                        img: './img/img Carros/img Ford/FordFusion.png',
                        desc: 'O Ford Fusion é um sedã médio elegante, com design sofisticado e interior espaçoso. Oferece tecnologias avançadas de segurança e conectividade, além de motorização eficiente, tornando-se uma escolha confortável e estilosa.',
                        descontPrice: 'de R$ 1.750,00 por ',
                        price: 'R$ 1.050,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.5 Flex, 2.0 Turbo ou 2.5 Híbrido',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Fusion 2.webp',
                        img3: './img/img Interior/Ford/Ford Fusion 3.avif',
                        img4: './img/img Interior/Ford/Ford Fusion 4.jpg'
                    },
                    {
                        title: 'Ford Ka Sedan',
                        img: './img/img Carros/img Ford/FordKaSedan.png',
                        desc: 'O Ford Ka Sedan é um compacto que combina praticidade e conforto. Com um design moderno e amplo espaço interno, oferece bom desempenho e eficiência de combustível. É ideal para o dia a dia, com tecnologias úteis e um bom porta-malas, tornando-se uma escolha acessível e funcional.',
                        descontPrice: 'de R$ 1.050,00 por ',
                        price: 'R$ 630,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Ka Sedan 2.jpg',
                        img3: './img/img Interior/Ford/Ford Ka Sedan 3.jpg',
                        img4: './img/img Interior/Ford/Ford Ka Sedan 4.webp'
                    },
                    {
                        title: 'Ford Focus',
                        img: './img/img Carros/img Ford/Ford Focus.png',
                        desc: 'O Ford Focus é um hatchback compacto, ágil e econômico. Com boa performance e tecnologia avançada, é ideal para o uso diário, oferecendo também versões mais esportivas para quem busca uma condução mais dinâmica.',
                        descontPrice: 'de R$ 1.500,00 por ',
                        price: 'R$ 1.100,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Focus 2.jpg',
                        img3: './img/img Interior/Ford/Ford Focus 3.webp',
                        img4: './img/img Interior/Ford/Ford Focus 4.jpg'
                    },
                    {
                        title: 'Ford Mustang',
                        img: './img/img Carros/img Ford/Ford Mustang.png',
                        desc: 'O Ford Mustang é um clássico dos carros esportivos, com design imponente e motores potentes. Oferece uma experiência de direção emocionante, combinando estilo e performance para quem busca adrenalina e um visual marcante.',
                        descontPrice: 'de R$ 4.000,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: '5.0 V8',
                        cambio: 'Manual de 6 marchas ou automático de 10 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Mustang 2.jpg',
                        img3: './img/img Interior/Ford/Ford Mustang 3.jpg',
                        img4: './img/img Interior/Ford/Ford Mustang 4.webp'
                    },
                    {
                        title: 'Ford Ranger',
                        img: './img/img Carros/img Ford/Ford Ranger.png',
                        desc: 'A Ford Ranger é uma picape robusta e versátil, com ótima capacidade de carga e desempenho off-road. Confortável e bem equipada, é ideal tanto para trabalho pesado quanto para o uso diário e aventuras.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.2 Diesel ou 3.2 Diesel',
                        cambio: 'Manual de 6 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Ranger 2.webp',
                        img3: './img/img Interior/Ford/Ford Ranger 3.jpg',
                        img4: './img/img Interior/Ford/Ford Ranger 4.jpg'
                    },
                    {
                        title: 'Ford Edge',
                        img: './img/img Carros/img Ford/Ford Edge.png',
                        desc: 'O Ford Edge é um SUV médio, com design sofisticado e boa performance. Oferece conforto, espaço e tecnologias avançadas, sendo uma opção equilibrada para quem busca um SUV moderno e confortável.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.0 Turbo',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Edge 2.jpg',
                        img3: './img/img Interior/Ford/Ford Edge 3.jpg',
                        img4: './img/img Interior/Ford/Ford Edge 4.avif'
                    },
                    {
                        title: 'Ford Explorer',
                        img: './img/img Carros/img Ford/Ford Explorer.png',
                        desc: 'O Ford Explorer é um SUV grande, ideal para famílias ou viagens longas. Com interior espaçoso, motorização potente e tração integral, é perfeito tanto para a cidade quanto para terrenos desafiadores.',
                        descontPrice: 'de R$ 3.500,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '3.5 V6 Turbo',
                        cambio: 'Automático de 10 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Ford/Ford Explorer 2.jpg',
                        img3: './img/img Interior/Ford/Ford Explorer 3.jpeg',
                        img4: './img/img Interior/Ford/Ford Explorer 4.avif'
                    },
                ],
                'Chevrolet': [{
                        title: 'Chevrolet Onix',
                        img: './img/img Carros/img Chevrolet/Onix.png',
                        desc: 'O Chevrolet Onix é um hatchback compacto popular no Brasil, conhecido por seu design moderno, eficiência de combustível e tecnologia avançada. Com um interior espaçoso e várias opções de versões, ele atende diferentes perfis de consumidores, unindo economia e conforto.',
                        descontPrice: 'de R$ 1.260,00 por ',
                        price: 'R$ 700,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo ou 1.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Onix 2.jpg',
                        img3: './img/img Interior/Chevrolet/Chevrolet Onix 3.webp',
                        img4: './img/img Interior/Chevrolet/Chevrolet Onix 4.jpg'
                    },
                    {
                        title: 'Chevrolet Tracker Lt',
                        img: './img/img Carros/img Chevrolet/TrackerLt.png',
                        desc: 'O Chevrolet Tracker LT é um SUV compacto com design moderno e interior espaçoso. Destaca-se pela tecnologia avançada, como tela touchscreen e conectividade, além de oferecer boa segurança. É uma opção prática e econômica para o dia a dia.',
                        descontPrice: 'de R$ 1.960,00 por ',
                        price: 'R$ 1.260,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Tracker Lt 2.webp',
                        img3: './img/img Interior/Chevrolet/Chevrolet Tracker Lt 3.jpg',
                        img4: './img/img Interior/Chevrolet/Chevrolet Tracker Lt 4.jpg'
                    },
                    {
                        title: 'Chevrolet Tracker Premier',
                        img: './img/img Carros/img Chevrolet/TrackerPremier.png',
                        desc: 'O Chevrolet Tracker Premier é a versão topo de linha do SUV compacto, com design sofisticado e interior bem equipado. Destaca-se pela tecnologia avançada e um motor eficiente, proporcionando uma condução ágil e estilosa.',
                        descontPrice: 'de R$ 2.100,00 por ',
                        price: 'R$ 1.400,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo Flex ou 1.2 Turbo Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Tracker Premier 2.webp',
                        img3: './img/img Interior/Chevrolet/Chevrolet Tracker Premier 3.webp',
                        img4: './img/img Interior/Chevrolet/Chevrolet Tracker Premier 4.webp'
                    },
                    {
                        title: 'Chevrolet Montana Premier',
                        img: './img/img Carros/img Chevrolet/MontanaPremier.png',
                        desc: 'O Chevrolet Montana Premier é uma picape compacta que une robustez e versatilidade. Com design moderno e interior espaçoso, conta com tecnologia avançada e recursos de segurança. Seu motor eficiente oferece desempenho ágil, ideal para uso urbano e atividades mais exigentes.',
                        descontPrice: 'de R$ 1.750,00 por ',
                        price: 'R$ 1.050,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.2 Turbo Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Montana Premier 2.jpg',
                        img3: './img/img Interior/Chevrolet/Chevrolet Montana Premier 3.jpg',
                        img4: './img/img Interior/Chevrolet/Chevrolet Montana Premier 4.avif'
                    },
                    {
                        title: 'Chevrolet Onix Plus Premier',
                        img: './img/img Carros/img Chevrolet/OnixPlusPremier.png',
                        desc: 'O Chevrolet Onix Plus Premier é a versão topo de linha do sedã compacto, com design moderno e interior espaçoso. Oferece tecnologia avançada e recursos de segurança, além de um motor eficiente para uma condução confortável e prática.',
                        descontPrice: 'de R$ 1.400,00 por ',
                        price: 'R$  840,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo Flex',
                        cambio: 'Câmbio: Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Onix Plus Premier 2.avif',
                        img3: './img/img Interior/Chevrolet/Chevrolet Onix Plus Premier 3.webp',
                        img4: './img/img Interior/Chevrolet/Chevrolet Onix Plus Premier 4.jpg'
                    },
                    {
                        title: 'Chevrolet Prisma',
                        img: './img/img Carros/img Chevrolet/Chevrolet Prisma.png',
                        desc: 'O Chevrolet Prisma é um sedã compacto com ótimo custo-benefício, destacando-se pelo conforto, bom desempenho e economia de combustível. Seu design sóbrio e interior espaçoso tornam-no uma opção popular para famílias pequenas e motoristas urbanos.',
                        descontPrice: 'de R$ 1.300,00 por ',
                        price: 'R$ 900,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.0 Turbo Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: '',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Prisma 2.webp',
                        img3: './img/img Interior/Chevrolet/Chevrolet Prisma 3.webp',
                        img4: './img/img Interior/Chevrolet/Chevrolet Prisma 4.jpg'
                    },
                    {
                        title: 'Chevrolet Spin',
                        img: './img/img Carros/img Chevrolet/Chevrolet Spin.png',
                        desc: 'O Chevrolet Spin é um monovolume espaçoso, ideal para famílias maiores. Com capacidade para até sete passageiros, oferece conforto, versatilidade e boa relação custo-benefício, tornando-o uma excelente opção para quem precisa de mais espaço sem abrir mão de economia.',
                        descontPrice: 'de R$ 1.800,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.8 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Spin 2.png',
                        img3: './img/img Interior/Chevrolet/Chevrolet Spin 3.avif',
                        img4: './img/img Interior/Chevrolet/Chevrolet Spin 4.webp'
                    },
                    {
                        title: 'Chevrolet Equinox',
                        img: './img/img Carros/img Chevrolet/Chevrolet Equinox.png',
                        desc: 'O Chevrolet Equinox é um SUV de porte médio, que combina sofisticação, conforto e desempenho. Equipado com tecnologias avançadas e motorizações potentes, é ideal para quem busca um veículo para longas viagens ou aventuras, com muito espaço e recursos de segurança.',
                        descontPrice: 'de R$ 3.000,00 por ',
                        price: 'R$ 2.000,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Turbo Flex ou 2.0 Turbo Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Equinox 2.webp',
                        img3: './img/img Interior/Chevrolet/Chevrolet Equinox 3.avif',
                        img4: './img/img Interior/Chevrolet/Chevrolet Equinox 4.webp'
                    },
                    {
                        title: 'Chevrolet Camaro',
                        img: './img/img Carros/img Chevrolet/Chevrolet Camaro.png',
                        desc: 'O Chevrolet Camaro é um ícone dos carros esportivos, com design agressivo e desempenho excepcional. Disponível em versões conversível e cupê, o Camaro oferece uma experiência de direção emocionante, com motorizações potentes e visual impactante.',
                        descontPrice: 'de R$ 5.000,00 por ',
                        price: 'R$ 3.500,00 ',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: '2.0 Turbo ou 6.2 V8',
                        cambio: 'Manual de 6 marchas ou automático de 10 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet Camaro 2.webp',
                        img3: './img/img Interior/Chevrolet/Chevrolet Camaro 3.jpg',
                        img4: './img/img Interior/Chevrolet/Chevrolet Camaro 4.webp'
                    },
                    {
                        title: 'Chevrolet S10',
                        img: './img/img Carros/img Chevrolet/Chevrolet S10.png',
                        desc: 'A Chevrolet S10 é uma picape robusta, com boa capacidade de carga e desempenho off-road. Ideal para quem precisa de um veículo de trabalho ou aventura, a S10 também oferece conforto e tecnologias avançadas no interior, tornando-a uma opção versátil.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.8 Turbo Diesel ou 2.5 Flex',
                        cambio: 'Manual de 6 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Chevrolet/Chevrolet S10 2.jpg',
                        img3: './img/img Interior/Chevrolet/Chevrolet S10 3.avif',
                        img4: './img/img Interior/Chevrolet/Chevrolet S10 4.avif'
                    },
                ],
                'Volkswagen': [{
                        title: 'Volkswagen Gol',
                        img: './img/img Carros/img Volkswagen/Gol.png',
                        desc: 'O Volkswagen Gol é um hatchback compacto reconhecido por sua eficiência e praticidade. Com design moderno, é um dos carros mais vendidos no Brasil, oferecendo bom desempenho e baixo custo de manutenção. Seu interior é espaçoso e confortável, perfeito para o dia a dia e viagens.',
                        descontPrice: 'de R$ 1.050,00 por ',
                        price: 'R$ 600,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Gol 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen Gol 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen Gol 4.webp'
                    },
                    {
                        title: 'Volkswagen Jetta',
                        img: './img/img Carros/img Volkswagen/Jetta.png',
                        desc: 'O Volkswagen Jetta é um sedã médio que une elegância e desempenho. Com design sofisticado e interior espaçoso, oferece conforto e tecnologia avançada. Seus motores potentes garantem uma condução prazerosa, tornando-o uma ótima escolha para viagens e uso diário.',
                        descontPrice: 'de R$ 2.100,00 por ',
                        price: 'R$ 1.400,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.4 Turbo Flex ou 2.0 Turbo',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Jetta 2.jpg',
                        img3: './img/img Interior/Volkswagen/Volkswagen Jetta 3.webp',
                        img4: './img/img Interior/Volkswagen/Volkswagen Jetta 4.jpg'
                    },
                    {
                        title: 'Volkswagen Polo',
                        img: './img/img Carros/img Volkswagen/Polo.png',
                        desc: 'O Volkswagen Polo é um hatchback compacto que une estilo e conforto. Com design moderno e interior espaçoso, oferece conectividade e segurança avançadas. Seu desempenho eficiente o torna uma excelente opção para o dia a dia.',
                        descontPrice: 'de R$ 1.260,00 por ',
                        price: 'R$ 700,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.0 Turbo',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Polo 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen Polo 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen Polo 4.jpg'
                    },
                    {
                        title: 'Volkswagen T-Cross',
                        img: './img/img Carros/img Volkswagen/T-Cross.png',
                        desc: 'O Volkswagen T-Cross é um SUV compacto que une praticidade e estilo. Com design moderno e interior espaçoso, é ideal para famílias. Oferece tecnologia avançada e uma condução confortável, sendo uma ótima escolha para o dia a dia',
                        descontPrice: 'de R$ 1.750,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo Flex ou 1.4 Turbo Flex',
                        cambio: 'Automático de 6 marchas ou automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen T-Cross 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen T-Cross 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen T-Cross 4.webp'
                    },
                    {
                        title: 'Volkswagen Virtus',
                        img: './img/img Carros/img Volkswagen/Virtus.png',
                        desc: 'O Volkswagen Virtus é um sedã elegante que une espaço e tecnologia. Com design sofisticado, seu interior é amplo e confortável, ideal para passageiros. Oferece recursos modernos de conectividade e segurança, tornando-se uma excelente opção para o dia a dia.',
                        descontPrice: 'de R$ 1.540,00 por ',
                        price: 'R$  1.050,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.6 Flex ou 1.0 Turbo Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Virtus 2.jpg',
                        img3: './img/img Interior/Volkswagen/Volkswagen Virtus 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen Virtus 4.webp'
                    },
                    {
                        title: 'Volkswagen Saveiro',
                        img: './img/img Carros/img Volkswagen/Volkswagen Saveiro.png',
                        desc: 'A Volkswagen Saveiro é uma picape compacta, ideal para quem precisa de um veículo de carga leve e versátil. Com design moderno e boa capacidade de carga, a Saveiro é perfeita para uso urbano e pequenas tarefas profissionais.',
                        descontPrice: 'de R$ 1.700,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.6 Flex ou 1.6 Turbo Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Saveiro 2.jpg',
                        img3: './img/img Interior/Volkswagen/Volkswagen Saveiro 3.webp',
                        img4: './img/img Interior/Volkswagen/Volkswagen Saveiro 4.jpeg'
                    },
                    {
                        title: 'Volkswagen Tiguan',
                        img: './img/img Carros/img Volkswagen/Volkswagen Tiguan.png',
                        desc: 'O Volkswagen Tiguan é um SUV médio de alta performance, com design sofisticado e motorizações potentes. Ideal para quem busca um SUV confortável e capaz, o Tiguan oferece ótima dirigibilidade e um interior espaçoso com recursos tecnológicos de ponta.',
                        descontPrice: 'de R$ 3.000,00 por ',
                        price: 'R$ 2.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.4 Turbo Flex ou 2.0 Turbo',
                        cambio: 'Automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Tiguan 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen Tiguan 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen Tiguan 4.jpg'
                    },
                    {
                        title: 'Volkswagen Passat',
                        img: './img/img Carros/img Volkswagen/Volkswagen Passat.png',
                        desc: 'O Passat é um sedã grande e luxuoso, oferecendo um alto nível de conforto, tecnologias avançadas e desempenho superior. Com um design sóbrio e interior refinado, o Passat é perfeito para quem busca um carro sofisticado, ideal para longas viagens ou uso executivo.',
                        descontPrice: 'de R$ 3.500,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.0 Turbo Flex',
                        cambio: 'Automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Passat 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen Passat 3.jpg',
                        img4: './img/img Interior/Volkswagen/Volkswagen Passat 4.jpg'
                    },
                    {
                        title: 'Volkswagen Nivus',
                        img: './img/img Carros/img Volkswagen/Volkswagen Nivus.png',
                        desc: 'O Volkswagen Nivus é um SUV compacto com design inovador e estilo esportivo. Com motorizações eficientes e uma boa lista de equipamentos, é uma opção para quem busca um carro moderno, com boa conectividade e eficiência de combustível.',
                        descontPrice: 'de R$ 1.900,00 por ',
                        price: 'R$ 1.400,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Turbo Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Nivus 2.webp',
                        img3: './img/img Interior/Volkswagen/Volkswagen Nivus 3.webp',
                        img4: './img/img Interior/Volkswagen/Volkswagen Nivus 4.webp'
                    },
                    {
                        title: 'Volkswagen Arteon',
                        img: './img/img Carros/img Volkswagen/Volkswagen Arteon.png',
                        desc: 'O Arteon é um sedã de luxo com design impressionante e performance esportiva. Com motorizações potentes e uma cabine luxuosa, o Arteon oferece uma experiência de condução refinada, sendo uma excelente opção para quem busca elegância e desempenho em um único pacote.',
                        descontPrice: 'de R$ 4.500,00 por ',
                        price: 'R$ 3.000,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.0 Turbo',
                        cambio: 'Automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Volkswagen/Volkswagen Arteon 2.jpg',
                        img3: './img/img Interior/Volkswagen/Volkswagen Arteon 3.webp',
                        img4: './img/img Interior/Volkswagen/Volkswagen Arteon 4.jpg'
                    },
                ],
                'Fiat': [{
                        title: 'Fiat Uno Way',
                        img: './img/img Carros/img Fiat/Fiat Uno.png',
                        desc: 'O Fiat Uno Way é a versão aventureira do Uno, com suspensão elevada e design robusto. Compacto, econômico e confortável, é ideal para quem busca versatilidade e baixo custo de manutenção.',
                        descontPrice: 'de R$ 700,00 por',
                        price: 'R$ 500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.3 Flex',
                        cambio: 'Manual de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Uno Way 2.webp',
                        img3: './img/img Interior/Fiat/Fiat Uno Way 3.webp',
                        img4: './img/img Interior/Fiat/Fiat Uno Way 4.webp'
                    },
                    {
                        title: 'Fiat Palio',
                        img: './img/img Carros/img Fiat/Fiat Palio.png',
                        desc: 'O Fiat Palio é um carro compacto, robusto e econômico, ideal para o uso urbano. Lançado em 1996, oferece versatilidade, baixo custo de manutenção e opções hatch e sedan (Palio Siena). É uma escolha prática e acessível para quem busca confiabilidade no dia a dia.',
                        descontPrice: 'de R$ 750,00 por',
                        price: 'R$ 550,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Palio 2.webp',
                        img3: './img/img Interior/Fiat/Fiat Palio 3.webp',
                        img4: './img/img Interior/Fiat/Fiat Palio 4.jpeg'
                    },
                    {
                        title: 'Fiat Argo',
                        img: './img/img Carros/img Fiat/Fiat Argo.png',
                        desc: 'O Fiat Argo é um hatch compacto moderno, com design arrojado, bom desempenho e interior espaçoso. Lançado em 2017, oferece tecnologia avançada, motorização eficiente e itens como sistema multimídia e direção elétrica, sendo uma opção econômica e prática para o dia a dia.',
                        descontPrice: 'de R$ 1.000,00 por ',
                        price: 'R$ 750,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'V8',
                        cambio: '1.0 Flex ou 1.3 Flex',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Argo 2.webp',
                        img3: './img/img Interior/Fiat/Fiat Argo 3.jpg',
                        img4: './img/img Interior/Fiat/Fiat Argo 4.jpg'
                    },
                    {
                        title: 'Fiat Toro',
                        img: './img/img Carros/img Fiat/Fiat Toro.png',
                        desc: 'A Fiat Toro é uma picape média robusta e moderna, ideal para trabalho e lazer. Com motorização 1.8 e 2.0, oferece conforto, tecnologia e capacidade para diversos terrenos.',
                        descontPrice: 'de R$ 1.500,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.8 Flex ou 2.0 Turbo Diesel',
                        cambio: 'Manual de 6 marchas ou automático de 9 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Toro 2.jpg',
                        img3: './img/img Interior/Fiat/Fiat Toro 3.jpg',
                        img4: './img/img Interior/Fiat/Fiat Toro 4.webp'
                    },
                    {
                        title: 'Fiat Siena',
                        img: './img/img Carros/img Fiat/Fiat Siena.png',
                        desc: 'O Fiat Siena é um sedan compacto, confortável e econômico. Lançado em 1997, oferece bom espaço interno e é ideal para quem busca um carro acessível e confiável para o dia a dia.',
                        descontPrice: 'de R$ 900,00 por ',
                        price: 'R$  650,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Siena 2.jpg',
                        img3: './img/img Interior/Fiat/Fiat Siena 3.jpg',
                        img4: './img/img Interior/Fiat/Fiat Siena 4.jpg'
                    },
                    {
                        title: 'Fiat Cronos',
                        img: './img/img Carros/img Fiat/Fiat Cronos.png',
                        desc: 'O Fiat Cronos é um sedã compacto que se destaca pelo seu espaço interno, conforto e bom custo-benefício. Com design elegante e uma condução suave, é ideal para quem busca um carro familiar e econômico.',
                        descontPrice: 'de R$ 1.800,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.3 Flex ou 1.8 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Cronos 2.webp',
                        img3: './img/img Interior/Fiat/Fiat Cronos 3.webp',
                        img4: './img/img Interior/Fiat/Fiat Cronos 4.jpg'
                    },
                    {
                        title: 'Fiat Mobi',
                        img: './img/img Carros/img Fiat/Fiat Mobi.png',
                        desc: 'O Fiat Mobi é um carro compacto e acessível, perfeito para quem precisa de um veículo ágil e econômico para o tráfego urbano. Com design moderno e baixo custo de manutenção, é uma excelente opção para quem busca praticidade.',
                        descontPrice: 'de R$ 1.000,00 por ',
                        price: 'R$ 700,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Mobi 2.jpg',
                        img3: './img/img Interior/Fiat/Fiat Mobi 3.webp',
                        img4: './img/img Interior/Fiat/Fiat Mobi 4.webp'
                    },
                    {
                        title: 'Fiat 500',
                        img: './img/img Carros/img Fiat/Fiat 500.png',
                        desc: 'O Fiat 500 é um hatchback pequeno, charmoso e cheio de estilo. Ideal para quem procura um carro compacto e moderno, com um toque de sofisticação e design icônico, é perfeito para a cidade e para quem busca um veículo diferenciado.',
                        descontPrice: 'de R$ 2.000,00 por ',
                        price: 'R$ 1.500,00 ',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: '1.4 Flex ou 1.3 Turbo Diesel',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat 500 2.jpg',
                        img3: './img/img Interior/Fiat/Fiat 500 3.jpg',
                        img4: './img/img Interior/Fiat/Fiat 500 4.jpg'
                    },
                    {
                        title: 'Fiat Ducato',
                        img: './img/img Carros/img Fiat/Fiat Ducato.png',
                        desc: 'O Fiat Ducato é uma van de grande porte, ideal para transporte de mercadorias e passageiros. Com excelente capacidade de carga e robustez, é um dos modelos preferidos para empresas que necessitam de um veículo utilitário de grande porte.',
                        descontPrice: 'de R$ 3.500,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '2.3 Turbo Diesel ou 2.2 Diesel',
                        cambio: 'Manual de 6 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Ducato 2.jpg',
                        img3: './img/img Interior/Fiat/Fiat Ducato 3.webp',
                        img4: './img/img Interior/Fiat/Fiat Ducato 4.webp'
                    },
                    {
                        title: 'Fiat Panda',
                        img: './img/img Carros/img Fiat/Fiat Panda.png',
                        desc: 'O Fiat Panda é um compacto que oferece grande praticidade, baixo consumo e um design simples e funcional. Ideal para quem precisa de um carro econômico e robusto, o Panda é perfeito para quem busca um veículo sem complicação.',
                        descontPrice: 'de R$ 1.200,00 por ',
                        price: 'R$ 800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.0 Flex ou 1.2 Flex',
                        cambio: 'Manual de 5 marchas ou automático',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Fiat/Fiat Panda 2.webp',
                        img3: './img/img Interior/Fiat/Fiat Panda 3.jpg',
                        img4: './img/img Interior/Fiat/Fiat Panda 4.webp'
                    },
                ],
                'Honda': [{
                        title: 'Honda Civic',
                        img: './img/img Carros/img Honda/Honda Civic.png',
                        desc: 'O Honda Civic é um sedan médio confiável, com design moderno e bom desempenho. Oferece motorização 1.5 turbo e 2.0, tecnologia avançada e recursos de segurança, sendo uma opção confortável e durável.',
                        descontPrice: 'de R$ 1.500,00 por',
                        price: 'R$ 1.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 i-VTEC ou Motor 1.5 Turbo i-VTEC',
                        cambio: 'Manual de 6 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda Civic 2.webp',
                        img3: './img/img Interior/Honda/Honda Civic 3.jpg',
                        img4: './img/img Interior/Honda/Honda Civic 4.webp'
                    },
                    {
                        title: 'Honda HR-V',
                        img: './img/img Carros/img Honda/Honda HR-V.png',
                        desc: 'O Honda HR-V é um SUV compacto, elegante e confortável, com motorização 1.8. Oferece bom desempenho, tecnologia avançada e amplo espaço interno, sendo ideal para quem busca um carro prático e versátil.',
                        descontPrice: 'de R$ 1.600,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Flex',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda HR-V 2.webp',
                        img3: './img/img Interior/Honda/Honda HR-V 3.jpg',
                        img4: './img/img Interior/Honda/Honda HR-V 4.webp'
                    },
                    {
                        title: 'Honda Fit',
                        img: './img/img Carros/img Honda/Honda Fit.png',
                        desc: 'O Honda Fit é um hatch compacto, econômico e versátil, com motorização 1.5. Oferece bom desempenho, amplo espaço interno e praticidade para o uso diário.',
                        descontPrice: 'de R$ 1.100,00 por',
                        price: 'R$ 800,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda Fit 2.webp',
                        img3: './img/img Interior/Honda/Honda Fit 3.jpg',
                        img4: './img/img Interior/Honda/Honda Fit 4.webp'
                    },
                    {
                        title: 'Honda City',
                        img: './img/img Carros/img Honda/Honda City.png',
                        desc: 'O Honda City é um sedan compacto, econômico e confortável, com motorização 1.5. Oferece bom desempenho, tecnologia e é ideal para o uso diário.',
                        descontPrice: 'de R$ 1.300,00 por',
                        price: 'R$ 1.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda City 2.webp',
                        img3: './img/img Interior/Honda/Honda City 3.jpg',
                        img4: './img/img Interior/Honda/Honda City 4.jpg'
                    },
                    {
                        title: 'Honda CR-V',
                        img: './img/img Carros/img Honda/Honda-CR-V.png',
                        desc: 'O Honda CR-V é um SUV médio, confortável e confiável, com motorização 2.0 ou 1.5 turbo. Oferece bom desempenho, interior espaçoso e tecnologia, sendo ideal para quem busca versatilidade e segurança.',
                        descontPrice: 'de R$ 2.200,00 por',
                        price: 'R$ 1.600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.5 Turbo',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda CR-V 2.jpg',
                        img3: './img/img Interior/Honda/Honda CR-V 3.webp',
                        img4: './img/img Interior/Honda/Honda CR-V 4.jpg'
                    },
                    {
                        title: 'Honda Accord',
                        img: './img/img Carros/img Honda/Honda Accord.png',
                        desc: 'O Honda Accord é um sedã grande, de luxo, que combina desempenho, conforto e elegância. Com motorizações potentes e um interior sofisticado, o Accord é perfeito para quem busca um carro executivo com um alto nível de sofisticação e tecnologia.',
                        descontPrice: 'de R$ 3.500,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.5 Turbo i-VTEC ou motor híbrido',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda Accord 2.webp',
                        img3: './img/img Interior/Honda/Honda Accord 3.png',
                        img4: './img/img Interior/Honda/Honda Accord 4.jpg'
                    },
                    {
                        title: 'Honda WR-V',
                        img: './img/img Carros/img Honda/Honda WR-V.png',
                        desc: 'O Honda WR-V é um crossover compacto, baseado no Fit, que oferece mais altura do solo e um design mais robusto. Ideal para quem quer um carro com a versatilidade de um SUV, mas com a economia de um hatchback.',
                        descontPrice: 'de R$ 2.000,00 por ',
                        price: 'R$ 1.400,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: '1.5 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda WR-V 2.jpg',
                        img3: './img/img Interior/Honda/Honda WR-V 3.webp',
                        img4: './img/img Interior/Honda/Honda WR-V 4.jpg'
                    },
                    {
                        title: 'Honda Pilot',
                        img: './img/img Carros/img Honda/Honda Pilot.png',
                        desc: 'O Honda Pilot é um SUV grande, com capacidade para até 8 passageiros, projetado para quem precisa de muito espaço e conforto. Equipado com motorização potente e tecnologias de segurança, é ideal para viagens longas e para famílias grandes.',
                        descontPrice: 'de R$ 4.000,00 por ',
                        price: 'R$ 3.000,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.5 V6',
                        cambio: 'Automático de 9 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda Pilot 2.webp',
                        img3: './img/img Interior/Honda/Honda Pilot 3.jpg',
                        img4: './img/img Interior/Honda/Honda Pilot 4.webp'
                    },
                    {
                        title: 'Honda Civic Type R',
                        img: './img/img Carros/img Honda/Honda Civic Type R.png',
                        desc: 'O Civic Type R é a versão esportiva e de alto desempenho do Honda Civic. Com motor turboalimentado e suspensão esportiva, o Type R é voltado para entusiastas de carros e oferece uma experiência de condução extremamente dinâmica e empolgante.',
                        descontPrice: 'de R$ 5.500,00 por ',
                        price: 'R$ 4.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Turbo i-VTEC',
                        cambio: 'Manual de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda Civic Type R 2.webp',
                        img3: './img/img Interior/Honda/Honda Civic Type R 3.webp',
                        img4: './img/img Interior/Honda/Honda Civic Type R 4.jpg'
                    },
                    {
                        title: 'Honda HR-V Hybrid',
                        img: './img/img Carros/img Honda/Honda HR-V Hybrid.png',
                        desc: 'O Honda HR-V Hybrid é a versão híbrida do popular SUV HR-V. Combinando a praticidade do HR-V com a economia de combustível e a eficiência de um motor híbrido, é ideal para quem busca um SUV com baixo impacto ambiental e custos operacionais reduzidos.',
                        descontPrice: 'de R$ 3.000,00 por ',
                        price: 'R$ 2.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor híbrido',
                        cambio: 'Transmissão automática CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Honda/Honda HR-V Hybrid 2.avif',
                        img3: './img/img Interior/Honda/Honda HR-V Hybrid 3.webp',
                        img4: './img/img Interior/Honda/Honda HR-V Hybrid 4.jpg'
                    },
                ],
                'Hyundai': [{
                        title: 'Hyundai HB20',
                        img: './img/img Carros/img Hyundai/HB20.png',
                        desc: 'O Hyundai HB20 é um hatch compacto popular no Brasil, conhecido pelo design moderno, economia de combustível e custo-benefício. Oferece várias versões de motores e câmbios, atendendo diferentes perfis de motoristas.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Turbo',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai HB20 2.webp',
                        img3: './img/img Interior/Hyundai/Hyundai HB20 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai HB20 4.jpg'
                    },
                    {
                        title: 'Hyundai Creta',
                        img: './img/img Carros/img Hyundai/Hyundai-Creta.png',
                        desc: 'O Hyundai Creta é um SUV compacto, reconhecido pelo design robusto, conforto e tecnologia, com opções de motores eficientes e recursos modernos de segurança.',
                        descontPrice: 'de R$ 1.300,00 por',
                        price: 'R$ 1.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Turbo ou 1.6 Flex',
                        cambio: 'Manual de 6 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Creta 2.webp',
                        img3: './img/img Interior/Hyundai/Hyundai Creta 3.webp',
                        img4: './img/img Interior/Hyundai/Hyundai Creta 4.jpg'
                    },
                    {
                        title: 'Hyundai Tucson',
                        img: './img/img Carros/img Hyundai/Hyundai-Tucson.png',
                        desc: 'O Hyundai Tucson é um SUV médio que combina design sofisticado, conforto e tecnologia avançada, com motor potente e bom espaço interno, ideal para famílias e uso urbano.',
                        descontPrice: 'de R$ 1.500,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex ou 1.6 Turbo híbrido',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Tucson 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Tucson 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai Tucson 4.webp'
                    },
                    {
                        title: 'Hyundai Santa Fe',
                        img: './img/img Carros/img Hyundai/Hyundai-Santa-Fe.png',
                        desc: 'O Hyundai Santa Fe é um SUV de porte médio-grande, conhecido pelo alto nível de conforto, tecnologia de ponta e espaço interno amplo. Com motor potente e acabamento sofisticado, é ideal para famílias que buscam desempenho e luxo em viagens e no uso diário.',
                        descontPrice: 'de R$ 2.000,00 por',
                        price: 'R$ 1.500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.5 Turbo ou 2.5 híbrido',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Santa Fe 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Santa Fe 3.webp',
                        img4: './img/img Interior/Hyundai/Hyundai Santa Fe 4.jpg'
                    },
                    {
                        title: 'Hyundai Elantra',
                        img: './img/img Carros/img Hyundai/Hyundai-Elantra.png',
                        desc: 'O Hyundai Elantra é um sedan compacto, que se destaca pelo design elegante, conforto e tecnologia, oferecendo bom desempenho e eficiência no consumo de combustível.',
                        descontPrice: 'de R$ 1.400,00 por',
                        price: 'R$ 1.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex ou motor híbrido 1.6',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Elantra 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Elantra 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai Elantra 4.webp'
                    },
                    {
                        title: 'Hyundai Kona',
                        img: './img/img Carros/img Hyundai/Hyundai Kona.png',
                        desc: 'O Hyundai Kona é um SUV compacto com estilo inovador e uma experiência de condução dinâmica. Disponível com opções de motorização híbrida e a gasolina, é uma excelente escolha para quem busca um SUV moderno, econômico e versátil.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.700,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex ou 1.6 Turbo',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Kona 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Kona 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai Kona 4.jpg'
                    },
                    {
                        title: 'Hyundai i30',
                        img: './img/img Carros/img Hyundai/Hyundai i30.png',
                        desc: 'O Hyundai i30 é um hatchback de médio porte com bom desempenho e interior espaçoso. Com recursos de conectividade e segurança, é ideal para quem procura um carro versátil, confortável e com ótimo custo-benefício no segmento de hatches.',
                        descontPrice: 'de R$ 1.800,00 por ',
                        price: 'R$ 1.300,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Turbo ou 2.0',
                        cambio: 'Manual de 6 marchas ou automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai i30 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai i30 3.webp',
                        img4: './img/img Interior/Hyundai/Hyundai i30 4.webp'
                    },
                    {
                        title: 'Hyundai Veloster',
                        img: './img/img Carros/img Hyundai/Hyundai Veloster.png',
                        desc: 'O Hyundai Veloster é um hatchback esportivo com design único e estilo marcante. Seu motor potente e características de desempenho fazem dele uma opção empolgante para quem busca um carro com apelo esportivo e originalidade.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '3',
                        engine: 'Motor 1.6 Turbo',
                        cambio: 'Manual de 6 marchas ou automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Veloster 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Veloster 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai Veloster 4.jpg'
                    },
                    {
                        title: 'Hyundai Azera',
                        img: './img/img Carros/img Hyundai/Hyundai Azera.png',
                        desc: 'O Hyundai Azera é um sedã grande de luxo, com design sofisticado, excelente acabamento e recursos avançados de conforto. Ideal para quem busca um carro executivo, com performance robusta e tecnologias de assistência ao motorista.',
                        descontPrice: 'de R$ 3.500,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.5 V6 ou 2.5 híbrido',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Azera 2.webp',
                        img3: './img/img Interior/Hyundai/Hyundai Azera 3.jpg',
                        img4: './img/img Interior/Hyundai/Hyundai Azera 4.jpeg'
                    },
                    {
                        title: 'Hyundai Nexo',
                        img: './img/img Carros/img Hyundai/Hyundai Nexo.png',
                        desc: 'O Hyundai Nexo é um SUV inovador e o modelo mais avançado da marca no Brasil, sendo movido a hidrogênio. O Nexo oferece zero emissão de gases poluentes, alto desempenho e uma excelente autonomia, sendo uma opção para quem busca um veículo eco-friendly e futurista.',
                        descontPrice: 'de R$ 5.000,00 por ',
                        price: 'R$ 3.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor elétrico alimentado por célula de combustível',
                        cambio: 'Automático',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Hyundai/Hyundai Nexo 2.jpg',
                        img3: './img/img Interior/Hyundai/Hyundai Nexo 3.avif',
                        img4: './img/img Interior/Hyundai/Hyundai Nexo 4.avif'
                    },
                ],
                'Renault': [{
                        title: 'Renault Kwid',
                        img: './img/img Carros/img Renault/Renault Kwid.png',
                        desc: 'O Renault Kwid é um carro compacto e econômico, ideal para o dia a dia urbano. Com design moderno, oferece baixo consumo de combustível e boa capacidade de carga, sendo uma opção acessível e prática para quem busca um veículo ágil e de baixo custo de manutenção.',
                        descontPrice: 'de R$ 700,00 por',
                        price: 'R$ 500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Kwid 2.webp',
                        img3: './img/img Interior/Renault/Renault Kwid 3.jpg',
                        img4: './img/img Interior/Renault/Renault Kwid 4.png'
                    },
                    {
                        title: 'Renault Sandero',
                        img: './img/img Carros/img Renault/Renault Sandero.png',
                        desc: 'O Renault Sandero é um hatchback espaçoso e acessível, com bom custo-benefício. Ele oferece um design moderno, amplo espaço interno e opções de motorização que atendem diferentes necessidades. Ideal para quem busca conforto, versatilidade e baixo custo de manutenção no dia a dia.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Sandero 2.jpg',
                        img3: './img/img Interior/Renault/Renault Sandero 3.jpg',
                        img4: './img/img Interior/Renault/Renault Sandero 4.jpg'
                    },
                    {
                        title: 'Renault Duster',
                        img: './img/img Carros/img Renault/Renault Duster.png',
                        desc: 'O Renault Duster é um SUV robusto e espaçoso, ideal para quem busca conforto e desempenho tanto na cidade quanto em terrenos mais difíceis. Com bom custo-benefício, oferece recursos modernos e versatilidade para diferentes necessidades.',
                        descontPrice: 'de R$ 1.200,00 por',
                        price: 'R$ 900,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 2.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Duster 2.jpg',
                        img3: './img/img Interior/Renault/Renault Duster 3.webp',
                        img4: './img/img Interior/Renault/Renault Duster 4.jpg'
                    },
                    {
                        title: 'Renault Captur',
                        img: './img/img Carros/img Renault/Renault Captur.png',
                        desc: 'O Renault Captur é um SUV compacto e sofisticado, com design moderno e recursos avançados de conectividade e segurança. Oferece conforto, bom desempenho e é ideal para quem busca um veículo versátil e de estilo.',
                        descontPrice: 'de R$ 1.400,00 por',
                        price: 'R$ 1.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 2.0 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Captur 2.jpg',
                        img3: './img/img Interior/Renault/Renault Captur 3.jpg',
                        img4: './img/img Interior/Renault/Renault Captur 4.png'
                    },
                    {
                        title: 'Renault Logan',
                        img: './img/img Carros/img Renault/Renault Logan.png',
                        desc: 'O Renault Logan é um sedã espaçoso e confortável, ideal para quem busca um carro com bom custo-benefício. Com amplo espaço interno e excelente capacidade de carga, oferece um desempenho eficiente e baixo custo de manutenção.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Logan 2.jfif',
                        img3: './img/img Interior/Renault/Renault Logan 3.jpg',
                        img4: './img/img Interior/Renault/Renault Logan 4.png'
                    },
                    {
                        title: 'Renault Clio',
                        img: './img/img Carros/img Renault/Renault Clio.png',
                        desc: 'Compacto e ágil, o Clio é um dos carros mais vendidos da Renault, oferecendo um excelente equilíbrio entre economia de combustível, design moderno e tecnologias de segurança. É ideal para quem busca um carro para o uso diário em ambientes urbanos.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 650,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Clio 2.jpg',
                        img3: './img/img Interior/Renault/Renault Clio 3.jpg',
                        img4: './img/img Interior/Renault/Renault Clio 4.webp'
                    },
                    {
                        title: 'Renault Talisman',
                        img: './img/img Carros/img Renault/Renault Talisman.png',
                        desc: 'O Talisman é um sedan de luxo da Renault, que oferece um nível superior de conforto e tecnologias avançadas. Seu design refinado, interior espaçoso e motorizações eficientes fazem dele uma opção sofisticada para quem valoriza conforto e desempenho.',
                        descontPrice: 'de R$ 2.000,00 por',
                        price: 'R$ 1.600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou 2.0 Flex',
                        cambio: 'Automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Talisman 2.jpg',
                        img3: './img/img Interior/Renault/Renault Talisman 3.jpg',
                        img4: './img/img Interior/Renault/Renault Talisman 4.jpg'
                    },
                    {
                        title: 'Renault Koleos',
                        img: './img/img Carros/img Renault/Renault Koleos.png',
                        desc: 'O Koleos é um SUV médio que se destaca pelo design sofisticado, amplo espaço interno e recursos tecnológicos de última geração. É uma opção para quem busca um carro mais imponente e confortável, com bom desempenho em viagens e uso diário.',
                        descontPrice: 'de R$ 1.800,00 por',
                        price: 'R$ 1.500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex ou 2.5 Flex',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Koleos 2.webp',
                        img3: './img/img Interior/Renault/Renault Koleos 3.jpg',
                        img4: './img/img Interior/Renault/Renault Koleos 4.webp'
                    },
                    {
                        title: 'Renault Fluence',
                        img: './img/img Carros/img Renault/Renault Fluence.png',
                        desc: 'O Fluence é um sedan médio que oferece um excelente nível de conforto, desempenho e tecnologias de conectividade. Seu design elegante e motor eficiente fazem dele uma boa opção para quem precisa de um carro familiar, mas com toque de sofisticação.',
                        descontPrice: 'de R$ 1.400,00 por',
                        price: 'R$ 1.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Fluence 2.webp',
                        img3: './img/img Interior/Renault/Renault Fluence 3.avif',
                        img4: './img/img Interior/Renault/Renault Fluence 4.webp'
                    },
                    {
                        title: 'Renault Zoe',
                        img: './img/img Carros/img Renault/Renault Zoe.png',
                        desc: 'O Zoe é um carro 100% elétrico, pequeno e ideal para o ambiente urbano. Com design futurista e autonomia de bateria interessante para o uso diário, é uma das opções mais acessíveis no segmento de carros elétricos, principalmente na Europa.',
                        descontPrice: 'de R$ 1.600,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor elétrico',
                        cambio: 'Automático',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Renault/Renault Zoe 2.webp',
                        img3: './img/img Interior/Renault/Renault Zoe 3.jpeg',
                        img4: './img/img Interior/Renault/Renault Zoe 4.jpg'
                    },
                ],
                'Nissan': [{
                        title: 'Nissan Versa',
                        img: './img/img Carros/img Nissan/Nissan Versa.png',
                        desc: 'O Nissan Versa é um sedã compacto que combina conforto, espaço interno generoso e tecnologias de segurança. Com design moderno e consumo eficiente, ele é ideal para quem busca um carro versátil e econômico para o uso urbano e viagens, mantendo um bom custo-benefício.',
                        descontPrice: 'de R$ 900,00 por',
                        price: 'R$ 700,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Versa 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Versa 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Versa 4.jpg'
                    },
                    {
                        title: 'Nissan Kicks',
                        img: './img/img Carros/img Nissan/Nissan Kicks.png',
                        desc: 'O Nissan Kicks é um SUV compacto, econômico e confortável, com design moderno e tecnologias de segurança, ideal para uso urbano.',
                        descontPrice: 'de R$ 1.200,00 por',
                        price: 'R$ 900,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Kicks 2.avif',
                        img3: './img/img Interior/Nissan/Nissan Kicks 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Kicks 4.jpg'
                    },
                    {
                        title: 'Nissan Sentra',
                        img: './img/img Carros/img Nissan/Nissan Sentra.png',
                        desc: 'O Nissan Sentra é um sedã médio que oferece conforto, design elegante e tecnologias de segurança, ideal para quem busca sofisticação e desempenho no dia a dia.',
                        descontPrice: 'de R$ 1.400,00 por',
                        price: 'R$ 1.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex',
                        cambio: 'Manual de 6 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Sentra 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Sentra 3.webp',
                        img4: './img/img Interior/Nissan/Nissan Sentra 4.webp'
                    },
                    {
                        title: 'Nissan Frontier',
                        img: './img/img Carros/img Nissan/Nissan Frontier.png',
                        desc: 'A Nissan Frontier é uma picape robusta e versátil, ideal para trabalho e aventuras off-road. Oferece potência, conforto e tecnologias modernas, garantindo bom desempenho em qualquer terreno.',
                        descontPrice: 'de R$ 1.800,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.3 Turbo Diesel ou 2.5 Flex',
                        cambio: 'Manual de 6 marchas ou automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Frontier 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Frontier 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Frontier 4.jpg'
                    },
                    {
                        title: 'Nissan Leaf',
                        img: './img/img Carros/img Nissan/Nissan Leaf.png',
                        desc: 'O Nissan Leaf é um carro elétrico pioneiro, com design moderno e tecnologia avançada. É silencioso, sustentável e ideal para quem busca um veículo eficiente e ecológico, com autonomia e desempenho adequados para o uso urbano.',
                        descontPrice: 'de R$ 2.000,00 por',
                        price: 'R$ 1.500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor elétrico',
                        cambio: 'Automático',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Leaf 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Leaf 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Leaf 4.jpg'
                    },
                    {
                        title: 'Nissan March',
                        img: './img/img Carros/img Nissan/Nissan March.png',
                        desc: 'O Nissan March é um hatch compacto, ideal para quem busca um carro econômico e ágil para o dia a dia. Com design moderno e ótimo consumo de combustível, é perfeito para quem precisa de praticidade para se locomover nas cidades.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 Flex ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan March 2.jpg',
                        img3: './img/img Interior/Nissan/Nissan March 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan March 4.jpg'
                    },
                    {
                        title: 'Nissan Pathfinder',
                        img: './img/img Carros/img Nissan/Nissan Pathfinder.png',
                        desc: 'O Nissan Pathfinder é um SUV grande, robusto e confortável, perfeito para viagens em família ou aventura. Com 7 lugares, amplo espaço interno e tecnologia avançada, oferece uma condução estável e segura em diversos tipos de terreno.',
                        descontPrice: 'de R$ 2.500,00 por',
                        price: 'R$ 2.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.5 V6',
                        cambio: 'Automático de 9 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Pathfinder 2.png',
                        img3: './img/img Interior/Nissan/Nissan Pathfinder 3.avif',
                        img4: './img/img Interior/Nissan/Nissan Pathfinder 4.jpg'
                    },
                    {
                        title: 'Nissan X-Trail',
                        img: './img/img Carros/img Nissan/Nissan X-Trail.png',
                        desc: 'O Nissan X-Trail é um SUV médio, com ótimo desempenho tanto na cidade quanto em estradas mais desafiadoras. Oferece um interior confortável, recursos de segurança de ponta e um excelente custo-benefício para quem busca um veículo versátil.',
                        descontPrice: 'de R$ 1.800,00 por',
                        price: 'R$ 1.400,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Flex ou 1.6 Turbo',
                        cambio: 'Manual de 6 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan X-Trail 2.webp',
                        img3: './img/img Interior/Nissan/Nissan X-Trail 3.webp',
                        img4: './img/img Interior/Nissan/Nissan X-Trail 4.jpg'
                    },
                    {
                        title: 'Nissan Altima',
                        img: './img/img Carros/img Nissan/Nissan Altima.png',
                        desc: 'O Nissan Altima é um sedã de luxo com design sofisticado, tecnologia avançada e um desempenho excepcional. Ideal para quem busca conforto, estilo e uma condução suave em longas distâncias, é uma excelente opção para executivos e viagens.',
                        descontPrice: 'de R$ 2.300,00 por',
                        price: 'R$ 1.800,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.5 ou 2.0 Turbo',
                        cambio: 'Automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Altima 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Altima 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Altima 4.png'
                    },
                    {
                        title: 'Nissan Skyline GT-R',
                        img: './img/img Carros/img Nissan/Nissan Skyline GT-R.png',
                        desc: 'O Nissan Skyline GT-R é um ícone dos carros esportivos, oferecendo desempenho de ponta, potência impressionante e uma experiência de direção única. Com um design agressivo e recursos de alta performance, é a escolha ideal para os amantes de velocidade e exclusividade.',
                        descontPrice: 'de R$ 6.000,00 por',
                        price: 'R$ 5.000,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor 3.8 V6 Biturbo',
                        cambio: 'Manual de 6 marchas ou automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Nissan/Nissan Skyline GT-R 2.webp',
                        img3: './img/img Interior/Nissan/Nissan Skyline GT-R 3.jpg',
                        img4: './img/img Interior/Nissan/Nissan Skyline GT-R 4.webp'
                    },
                ],
                'Peugeot': [{
                        title: 'Peugeot 208',
                        img: './img/img Carros/img Peugeot/Peugeot 208.png',
                        desc: 'O Peugeot 208 é um hatch compacto com design moderno e arrojado. Oferece bom desempenho, tecnologia de ponta e baixo consumo de combustível, sendo ideal para quem busca praticidade e estilo no dia a dia.',
                        descontPrice: 'de R$ 900,00 por',
                        price: 'R$ 700,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 208 2.avif',
                        img3: './img/img Interior/Peugeot/Peugeot 208 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 208 4.webp'
                    },
                    {
                        title: 'Peugeot 3008',
                        img: './img/img Carros/img Peugeot/Peugeot 3008.png',
                        desc: 'O Peugeot 3008 é um SUV médio de luxo, com design sofisticado e recursos avançados de conectividade e segurança. Seu interior é confortável e espaçoso, perfeito para quem busca um carro versátil para a família e viagens.',
                        descontPrice: 'de R$ 1.600,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo, 1.5 Diesel ou versão híbrida',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 3008 2.webp',
                        img3: './img/img Interior/Peugeot/Peugeot 3008 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 3008 4.jpg'
                    },
                    {
                        title: 'Peugeot 2008',
                        img: './img/img Carros/img Peugeot/Peugeot 2008.png',
                        desc: 'O Peugeot 2008 é um SUV compacto, com visual elegante e eficiente no consumo de combustível. Ele oferece um bom equilíbrio entre conforto, desempenho e tecnologia, sendo ideal para quem precisa de um carro prático para a cidade.',
                        descontPrice: 'de R$ 1.200,00 por',
                        price: 'R$ 900,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 1.2 Turbo',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 2008 2.avif',
                        img3: './img/img Interior/Peugeot/Peugeot 2008 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 2008 4.jpg'
                    },
                    {
                        title: 'Peugeot 408',
                        img: './img/img Carros/img Peugeot/Peugeot 408.png',
                        desc: 'O Peugeot 408 é um sedã de design refinado, com amplo espaço interno e recursos de conforto. Seu motor oferece um bom desempenho, tornando-o uma excelente opção para quem procura um carro elegante e confortável para longas viagens e uso diário.',
                        descontPrice: 'de R$ 1.400,00 por',
                        price: 'R$ 1.000,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou 1.5 Diesel',
                        cambio: 'Manual de 6 marchas ou automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 408 2.webp',
                        img3: './img/img Interior/Peugeot/Peugeot 408 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 408 4.webp'
                    },
                    {
                        title: 'Peugeot Partner',
                        img: './img/img Carros/img Peugeot/Peugeot Partner.png',
                        desc: 'O Peugeot Partner é uma furgão robusta e versátil, projetada para o transporte de cargas e necessidades comerciais. Seu amplo espaço e praticidade tornam-no ideal para pequenos negócios e para quem precisa de um veículo funcional e resistente.',
                        descontPrice: 'de R$ 1.500,00 por',
                        price: 'R$ 1.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 1.5 Diesel',
                        cambio: 'Manual de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot Partner 2.jpg',
                        img3: './img/img Interior/Peugeot/Peugeot Partner 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot Partner 4.jpg'
                    },
                    {
                        title: 'Peugeot 5008',
                        img: './img/img Carros/img Peugeot/Peugeot 5008.png',
                        desc: 'O Peugeot 5008 é um SUV de 7 lugares, projetado para famílias grandes. Oferece conforto, segurança e tecnologia, com um design moderno e versatilidade para longas viagens ou passeios com a família.',
                        descontPrice: 'de R$ 4.000,00 por',
                        price: 'R$ 2.800,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo, 1.5 Diesel ou versão híbrida',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 5008 2.jpg',
                        img3: './img/img Interior/Peugeot/Peugeot 5008 3.webp',
                        img4: './img/img Interior/Peugeot/Peugeot 5008 4.jpg'
                    },
                    {
                        title: 'Peugeot 308',
                        img: './img/img Carros/img Peugeot/Peugeot 308.png',
                        desc: 'O Peugeot 308 é um hatch médio que impressiona pelo equilíbrio entre desempenho e conforto. Com um interior refinado e recursos tecnológicos, é uma excelente opção para quem busca um carro com bom custo-benefício e uma condução prazerosa.',
                        descontPrice: 'de R$ 1.800,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou 1.2 Turbo',
                        cambio: 'Manual de 6 marchas ou automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 308 2.jpg',
                        img3: './img/img Interior/Peugeot/Peugeot 308 3.webp',
                        img4: './img/img Interior/Peugeot/Peugeot 308 4.jpg'
                    },
                    {
                        title: 'Peugeot 508',
                        img: './img/img Carros/img Peugeot/Peugeot 508.png',
                        desc: 'O Peugeot 508 é um sedã premium com design refinado, tecnologia de ponta e desempenho superior. Oferece um interior luxuoso, ideal para quem procura um veículo de alto nível, com conforto e recursos avançados.',
                        descontPrice: 'de R$ 3.800,00 por',
                        price: 'R$ 2.500,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo, 2.0 Diesel ou versão híbrida',
                        cambio: 'Manual de 6 marchas ou automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 508 2.jpg',
                        img3: './img/img Interior/Peugeot/Peugeot 508 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 508 4.jpg'
                    },
                    {
                        title: 'Peugeot RCZ',
                        img: './img/img Carros/img Peugeot/Peugeot RCZ.png',
                        desc: 'O Peugeot RCZ é um coupé esportivo que chama a atenção pelo seu design arrojado e performance empolgante. Com motores potentes e uma experiência de direção envolvente, é a escolha para os entusiastas de carros esportivos.',
                        descontPrice: 'de R$ 4.500,00 por',
                        price: 'R$ 3.000,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor 1.6 Turbo ou 2.0 Turbo',
                        cambio: 'Manual de 6 marchas ou automático de 6 marchas',
                        arcondicionado: '',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot RCZ 2.webp',
                        img3: './img/img Interior/Peugeot/Peugeot RCZ 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot RCZ 4.webp'
                    },
                    {
                        title: 'Peugeot 106',
                        img: './img/img Carros/img Peugeot/Peugeot 106.png',
                        desc: 'O Peugeot 106 é um hatch compacto clássico, conhecido pela sua economia e facilidade de manobra, perfeito para quem busca um carro simples, ágil e acessível para o dia a dia nas cidades.',
                        descontPrice: 'de R$ 900,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor 1.0 ou 1.4 Flex',
                        cambio: 'Manual de 5 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Peugeot/Peugeot 106 2.jpeg',
                        img3: './img/img Interior/Peugeot/Peugeot 106 3.jpg',
                        img4: './img/img Interior/Peugeot/Peugeot 106 4.jpg'
                    },
                ],
                'Citroën': [{
                        title: 'Citroën C3',
                        img: './img/img Carros/img Citroen/Citroën C3.png',
                        desc: 'O Citroën C3 é um hatch compacto com design moderno e arrojado. Ideal para a cidade, ele oferece economia de combustível, boa conectividade e praticidade, sendo uma opção acessível para quem busca um carro urbano e versátil.',
                        descontPrice: 'de R$ 800,00 por',
                        price: 'R$ 600,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.0 ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático CVT',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën C3 2.webp',
                        img3: './img/img Interior/Citroën/Citroën C3 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën C3 4.jpg'
                    },
                    {
                        title: 'Citroën C4 Cactus',
                        img: './img/img Carros/img Citroen/Citroën C4 Cactus.png',
                        desc: 'O Citroën C4 Cactus é um SUV compacto com design inovador e original. Oferece conforto, boa altura do solo e tecnologias modernas, sendo perfeito para quem busca um veículo estiloso e confortável para uso urbano e pequenos passeios.',
                        descontPrice: 'de R$ 1.200,00 por',
                        price: 'R$ 900,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 1.6 Turbo',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën C4 Cactus 2.webp',
                        img3: './img/img Interior/Citroën/Citroën C4 Cactus 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën C4 Cactus 4.webp'
                    },
                    {
                        title: 'Citroën C5 Aircross',
                        img: './img/img Carros/img Citroen/Citroën C5 Aircross.png',
                        desc: 'O Citroën C5 Aircross é um SUV médio focado em conforto, oferecendo uma suspensão avançada e amplo espaço interno. Equipado com tecnologias de segurança e conectividade, é uma excelente opção para quem busca um carro versátil e confortável para a família.',
                        descontPrice: 'de R$ 1.600,00 por',
                        price: 'R$ 1.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou versão híbrida',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën C5 Aircross 2.jpg',
                        img3: './img/img Interior/Citroën/Citroën C5 Aircross 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën C5 Aircross 4.avif'
                    },
                    {
                        title: 'Citroën Berlingo',
                        img: './img/img Carros/img Citroen/Citroën Berlingo.png',
                        desc: 'O Citroën Berlingo é um furgão prático e espaçoso, ideal para quem precisa de um veículo para transporte de carga ou viagens com a família. Ele combina funcionalidade com conforto, sendo uma ótima opção para o trabalho ou lazer.',
                        descontPrice: 'de R$ 1.200,00 por',
                        price: 'R$ 900,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 1.5 Diesel',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën Berlingo 2.avif',
                        img3: './img/img Interior/Citroën/Citroën Berlingo 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën Berlingo 4.jpg'
                    },
                    {
                        title: 'Citroën Jumper',
                        img: './img/img Carros/img Citroen/Citroën Jumper.png',
                        desc: 'O Citroën Jumper é um furgão de grande porte, projetado para transportar cargas pesadas e volumosas. Com amplo espaço interno e excelente capacidade de carga, é ideal para uso comercial, atendendo a diversas necessidades empresariais.',
                        descontPrice: 'de R$ 2.000,00 por',
                        price: 'R$ 1.400,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.2 Diesel ou 2.0 Diesel',
                        cambio: 'Manual de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën Jumper 2.webp',
                        img3: './img/img Interior/Citroën/Citroën Jumper 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën Jumper 4.jpg'
                    },
                    {
                        title: 'Citroën C3 Aircross',
                        img: './img/img Carros/img Citroen/Citroën C3 Aircross.png',
                        desc: 'SUV compacto, com design robusto e uma proposta mais aventureira. Destaca-se pelo bom aproveitamento de espaço interno e pela sua versatilidade, com características que o tornam ideal para famílias pequenas ou como carro urbano.',
                        descontPrice: 'de R$ 1.600,00 por ',
                        price: 'R$ 1.200,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Flex ou 1.2 Turbo',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën C3 Aircross 2.jpg',
                        img3: './img/img Interior/Citroën/Citroën C3 Aircross 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën C3 Aircross 4.jpg'
                    },
                    {
                        title: 'Citroën Jumpy',
                        img: './img/img Carros/img Citroen/Citroën Jumpy.png',
                        desc: 'Furgão de médio porte, focado no uso comercial, com boa capacidade de carga e versatilidade. A Jumpy é bastante apreciada por empresas que necessitam de um veículo robusto para transporte de mercadorias e passageiros.',
                        descontPrice: 'de R$ 2.000,00 por ',
                        price: 'R$ 1.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Diesel',
                        cambio: 'Manual de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën Jumpy 2.webp',
                        img3: './img/img Interior/Citroën/Citroën Jumpy 3.webp',
                        img4: './img/img Interior/Citroën/Citroën Jumpy 4.jpg'
                    },
                    {
                        title: 'Citroën DS3',
                        img: './img/img Carros/img Citroen/Citroën DS3.png',
                        desc: 'Hatch compacto esportivo da linha DS, que é uma sub-marca premium da Citroën. Com design sofisticado e uma condução dinâmica, o DS3 se destaca pela personalização e pela experiência de direção mais esportiva.',
                        descontPrice: 'de R$ 2.000,00 por ',
                        price: 'R$ 1.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou 1.6 Flex',
                        cambio: 'Manual de 5 marchas ou automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën DS3 2.webp',
                        img3: './img/img Interior/Citroën/Citroën DS3 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën DS3 4.webp'
                    },
                    {
                        title: 'Citroën DS7 Crossback',
                        img: './img/img Carros/img Citroen/Citroën DS7 Crossback.png',
                        desc: 'SUV de luxo que combina conforto, tecnologia e um visual elegante. Parte da linha DS, é voltado para quem busca um veículo com um toque premium, oferecendo recursos avançados como o sistema de condução autônoma e uma experiência de interior refinada.',
                        descontPrice: 'de R$ 3.000,00 por ',
                        price: 'R$ 2.500,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo, 2.0 Diesel ou versão híbrida',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën DS7 Crossback 2.jpg',
                        img3: './img/img Interior/Citroën/Citroën DS7 Crossback 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën DS7 Crossback 4.jpg'
                    },
                    {
                        title: 'Citroën Grand C4 Picasso',
                        img: './img/img Carros/img Citroen/Citroën Grand C4 Picasso.png',
                        desc: 'Monovolume de sete lugares que oferece muito espaço e conforto, ideal para famílias grandes. Com um design elegante e recursos como a tecnologia Grip Control para melhorar a tração, é um carro focado na praticidade e conforto em viagens longas.',
                        descontPrice: 'de R$ 2.500,00 por ',
                        price: 'R$ 1.800,00 ',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 1.6 Turbo ou 2.0 Diesel',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Citroën/Citroën Grand C4 Picasso 2.jpg',
                        img3: './img/img Interior/Citroën/Citroën Grand C4 Picasso 3.jpg',
                        img4: './img/img Interior/Citroën/Citroën Grand C4 Picasso 4.webp'
                    },
                ],
                'BlackFriday': [{
                        title: 'Audi A8',
                        img: './img/img Carros/img Black Friday/Audi A8.png',
                        desc: 'O Audi A8 é um sedã de luxo de grande porte, que oferece um alto nível de conforto, desempenho e tecnologia. Equipado com recursos de última geração e uma motorização potente, é perfeito para quem deseja um carro de luxo com foco em sofisticação.',
                        descontPrice: 'de R$ 4.200,00 por',
                        price: 'R$ 2.400,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.0 V6',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Audi A8 2.jpg',
                        img3: './img/img Interior/Black Friday/Audi A8 3.jpg',
                        img4: './img/img Interior/Black Friday/Audi A8 4.jpg'
                    },
                    {
                        title: 'Volvo C40 Recharge',
                        img: './img/img Carros/img Black Friday/Volvo C40 Recharge.png',
                        desc: 'O C40 Recharge é um SUV elétrico de luxo, com design arrojado e excelente autonomia. Oferece uma condução silenciosa e sustentável, sem abrir mão da performance e sofisticação.',
                        descontPrice: 'de R$ 4.200,00 por',
                        price: 'R$ 2.400,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor elétrico',
                        cambio: 'Automático',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Volvo C40 Recharge 2.jpg',
                        img3: './img/img Interior/Black Friday/Volvo C40 Recharge 3.jpg',
                        img4: './img/img Interior/Black Friday/Volvo C40 Recharge 4.jpeg'
                    },
                    {
                        title: 'Audi RS7',
                        img: './img/img Carros/img Black Friday/Audi RS7.png',
                        desc: 'O Audi RS7 é um sedã esportivo de alto desempenho, com motor potente e design agressivo. Oferece uma experiência de direção emocionante, com tecnologias avançadas e um visual imbatível.',
                        descontPrice: 'de R$ 5.500,00 por',
                        price: 'R$ 3.050,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 4.0 V8 Biturbo',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Audi RS7 2.jpg',
                        img3: './img/img Interior/Black Friday/Audi RS7 3.jpg',
                        img4: './img/img Interior/Black Friday/Audi RS7 4.jpg'
                    },
                    {
                        title: 'Lexus LC',
                        img: './img/img Carros/img Black Friday/Lexus LC.png',
                        desc: 'O Lexus LC é um coupé de luxo, com motor potente e design impressionante. Seu desempenho e estética sofisticada fazem dele uma escolha ideal para quem deseja uma experiência de condução emocionante e estilosa.',
                        descontPrice: 'de R$ 5.500,00 por',
                        price: 'R$ 3.050,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor 5.0 V8 ou híbrido 3.5 V6',
                        cambio: 'Automático de 10 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Lexus LC 2.jpg',
                        img3: './img/img Interior/Black Friday/Lexus LC 3.jpg',
                        img4: './img/img Interior/Black Friday/Lexus LC 4.webp'
                    },
                    {
                        title: 'BMW i8',
                        img: './img/img Carros/img Black Friday/BMW i8.png',
                        desc: 'O BMW i8 é um supercarro híbrido de luxo, com design futurista e desempenho impressionante. Com sua motorização híbrida e tecnologias inovadoras, é uma escolha ideal para quem busca um carro esportivo de alto desempenho e com foco em sustentabilidade.',
                        descontPrice: 'de R$ 5.500,00 por',
                        price: 'R$ 3.050,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor híbrido',
                        cambio: 'Automático de 6 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/BMW i8 2.jpg',
                        img3: './img/img Interior/Black Friday/BMW i8 3.jpg',
                        img4: './img/img Interior/Black Friday/BMW i8 4.webp'
                    },
                    {
                        title: 'Mercedes-Benz AMG GT',
                        img: './img/img Carros/img Black Friday/Mercedes-Benz AMG GT.png',
                        desc: 'O AMG GT é um supercarro esportivo da Mercedes-Benz, projetado para proporcionar uma experiência de condução única. Com motor potente e design arrojado, é a escolha perfeita para quem busca performance e emoção ao volante.',
                        descontPrice: 'de R$ 7.000,00 por',
                        price: 'R$ 3.800,00',
                        diaria: 'R$ ',
                        doors: '2',
                        engine: 'Motor 4.0 V8 Biturbo ou versão híbrida',
                        cambio: 'Automático de 7 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Mercedes-Benz AMG GT 2.webp',
                        img3: './img/img Interior/Black Friday/Mercedes-Benz AMG GT 3.webp',
                        img4: './img/img Interior/Black Friday/Mercedes-Benz AMG GT 4.avif'
                    },
                    {
                        title: 'BMW X6',
                        img: './img/img Carros/img Black Friday/BMW X6.png',
                        desc: 'O BMW X6 é um SUV de luxo de design arrojado e performance excepcional. Com seu perfil esportivo e potente motorização, oferece uma experiência de condução emocionante, sem abrir mão do conforto e da tecnologia.',
                        descontPrice: 'de R$ 3.600,00 por',
                        price: 'R$ 2.100,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.0 Turbo',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/BMW X6 2.jpg',
                        img3: './img/img Interior/Black Friday/BMW X6 3.webp',
                        img4: './img/img Interior/Black Friday/BMW X6 4.jpg'
                    },
                    {
                        title: 'Volvo V90',
                        img: './img/img Carros/img Black Friday/Volvo V90.png',
                        desc: 'O V90 é um station wagon de luxo de grande porte, com excelente capacidade de carga e um interior luxuoso. Oferece desempenho refinado, tecnologia de ponta e um design elegante e prático.',
                        descontPrice: 'de R$ 3.800,00 por',
                        price: 'R$ 2.200,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 2.0 Turbo, híbrido ou 2.0 Diesel',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Volvo V90 2.webp',
                        img3: './img/img Interior/Black Friday/Volvo V90 3.jpg',
                        img4: './img/img Interior/Black Friday/Volvo V90 4.jpg'
                    },
                    {
                        title: 'Lexus LX',
                        img: './img/img Carros/img Black Friday/Lexus LX.png',
                        desc: 'O Lexus LX é um SUV de luxo de grande porte, com capacidade para enfrentar qualquer terreno sem abrir mão do conforto. Com motorização potente e um interior luxuoso, é perfeito para quem busca um veículo imponente e versátil.',
                        descontPrice: 'de R$ 5.500,00 por',
                        price: 'R$ 3.050,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 5.7 V8',
                        cambio: 'Automático de 8 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Lexus LX 2.webp',
                        img3: './img/img Interior/Black Friday/Lexus LX 3.jpg',
                        img4: './img/img Interior/Black Friday/Lexus LX 4.webp'
                    },
                    {
                        title: 'Mercedes-Benz S-Class',
                        img: './img/img Carros/img Black Friday/Mercedes-Benz S-Class.png',
                        desc: 'O S-Class é o ápice do luxo da Mercedes-Benz, oferecendo uma experiência de condução impecável. Com motorização potente, tecnologia de ponta e um interior de altíssimo nível, é um dos sedãs mais luxuosos do mundo.',
                        descontPrice: 'de R$ 5.000,00 por',
                        price: 'R$ 2.800,00',
                        diaria: 'R$ ',
                        doors: '4',
                        engine: 'Motor 3.0 V6, 4.0 V8 ou 6.0 V12',
                        cambio: 'Automático de 9 marchas',
                        arcondicionado: 'Disponível',
                        combustivel: 'mesma quantidade',
                        quilometragem: 'Inclui quilometragem ilimitada',
                        seguro: 'Seguro CDW',
                        img2: './img/img Interior/Black Friday/Mercedes-Benz S-Class 2.jpg',
                        img3: './img/img Interior/Black Friday/Mercedes-Benz S-Class 3.jpg',
                        img4: './img/img Interior/Black Friday/Mercedes-Benz S-Class 4.jpg'
                    },

                ]
            };
            // Atualiza a variável diaria com base no price
            for (const brand in vehicles) {
                vehicles[brand].forEach(vehicle => {
                    if (vehicle.price) {
                        const price = parseFloat(vehicle.price.replace('R$', '').replace('.', '').replace(',', '.')); // Converte R$ para número
                        vehicle.diaria = `R$ ${(price / 7).toFixed(2).replace('.', ',')}`;
                    }
                });
            }
            let cardsGenerated = {
                'Toyota': false,
                'Ford': false,
                'Chevrolet': false,
                'Volkswagen': false,
                'Fiat': false,
                'Honda': false,
                'Hyundai': false,
                'Renault': false,
                'Nisan': false,
                'Peugeot': false,
                'Citroën': false,
                'BlackFriday': false,

            };

            function addCards(number, type, button) {
                removeAllCards(); // Remove todos os cards antes de adicionar novos
                // Esconde a seção none-wallet
                document.querySelector('.none-wallet').style.display = 'none';
                if (!cardsGenerated[type]) {
                    const cardContainer = document.getElementById('card-container');
                    for (let i = 0; i < number; i++) {
                        const vehicle = vehicles[type][i];
                        const newCard = document.createElement('div');
                        newCard.className = 'card';
                        newCard.dataset.type = type;
                        // Adiciona a classe black-friday se for do tipo BlackFriday
                        if (type === 'BlackFriday') {
                            newCard.classList.add('black-friday');
                        }
                        newCard.innerHTML = `
                            <img src="${vehicle.img}" alt="Imagem do carro ${type} ${i+1}">
                            <div class="card-info">
                                <h2>${vehicle.title}</h2>
                                <p>${vehicle.desc}</p>
                            </div>
                            <div class="card-price">
                                <p id="desconto">${vehicle.descontPrice}</p>
                                <p>${vehicle.price}</p>
                                <p id="diaria">${vehicle.diaria} por dia</p>
                                <div class="buttons" id="buttons"><button class="reservar" onclick="reserveVehicle(${i}, '${type}')">Reservar<span></span></button></div>
                            </div>
                        `;
                        cardContainer.appendChild(newCard);
                    }
                    cardsGenerated[type] = true;
                }
                setActiveButton(button); // Define o botão como ativo
            }

            function reserveVehicle(index, type) {
                const vehicle = vehicles[type][index];
                localStorage.setItem('selectedVehicle', JSON.stringify(vehicle));
                window.location.href = 'detalhes.php';
            }

            function removeAllCards() {
                const cardContainer = document.getElementById('card-container');
                cardContainer.innerHTML = ''; // Remove todos os cards
                cardsGenerated = {
                    'Toyota': false,
                    'Ford': false,
                    'Chevrolet': false
                };
                resetActiveButtons(); // Reseta o estado dos botões
            }

            function setActiveButton(activeButton) {
                const buttons = document.querySelectorAll('.brand-button');
                buttons.forEach(button => {
                    button.classList.remove('active'); // Remove a classe ativa de todos os botões
                });
                activeButton.classList.add('active'); // Adiciona a classe ativa ao botão clicado
            }

            function resetActiveButtons() {
                const buttons = document.querySelectorAll('.brand-button');
                buttons.forEach(button => {
                    button.classList.remove('active'); // Remove a classe ativa de todos os botões
                });
            }
        </script>
        <!-- esse aqui é responsável pela imagem do carro quando o usuário nao clicou em nenhuma marca -->
        <section class="none-wallet">
            <h3>Nenhuma marca selecionada</h3>
            <p>Por favor selecione um dos botões acima para continuar.</p>
            <img src="./img/CarroPretoBranco.png">
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Logo e Slogan -->
            <div class="footer-section about">
                <h2>ExploraCar</h2>
                <p>Sua jornada começa aqui. Aluguel de carros com segurança e conforto.</p>
            </div>

            <!-- Links Rápidos -->
            <div class="footer-section links">
                <h3>Links Rápidos</h3>
                <ul>
                    <li><a href="../home/index.php">Início</a></li>
                    <li><a href="../loc/sobrenos/sobre.php">Sobre Nós</a></li>
                    <li><a href="../Locação/veiculos.php">Carros Disponíveis</a></li>
                    <li><a href="../loc/duvidasfrequentes/duvidas.php">Contato</a></li>
                </ul>
            </div>

            <!-- Contato -->
            <div class="footer-section contato">
                <h3>Contato</h3>
                <p>Rua Boa Vista, 825, São Caetano, SP</p>
                <p>Email: contato@exploracar.com</p>
                <p>Telefone: (11) 1234-5678</p>
            </div>

            <!-- Redes Sociais -->
            <div class="footer-section redes-sociais">
                <h3>Nos Siga</h3>
                <a id="correction" href="#"><img src="../global/img/face.png" width="30px" height="30px"
                        alt="Facebook"></a>
                <a href="#"><img src="../global/img/logoInsta.webp" width="50px" height="50px" alt="Instagram"></a>
                <a href="#"><img src="../global/img/linke.png" width="50px" height="50px" alt="LinkedIn"></a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy;
                ExploraCar | Todos os direitos reservados.</p>
            <ul>
                <li><a href="#">Termos de uso</a></li>
                <li><a href="../loc/politicas/politicas.html">Política de Privacidade</a></li>
                <li><a href="#">LGPD</a></li>
            </ul>
        </div>
    </footer>
    <script src="../global/global.js"></script>
</body>

</html>