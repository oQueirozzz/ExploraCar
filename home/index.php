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
            header("Location: ../locacao/veiculos.html");
            break;
        case 'sobre':
            header("Location: ../loc/sobrenos/sobre.php");
            break;
        case 'assinatura':
            header("Location: ../assinatura/assinatura.php");
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
    <title>ExploraCar</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
                        <button>
                            <input type="submit" name="cars" value="carros">
                            <img src="../global/img/carroICON.jpg" alt="veiculos" id="transparent">
                            <span>Carros</span>
                        </button>
                    </li>
                    <li>
                        <button type="submit" name="about" value="sobre">
                            <input type="submit" name="page" value="sobre">
                            <img src="../global/img/sobre.png" alt="Sobre">
                            <span>sobre nós</span>
                        </button>
                    </li>
                    <hr>


                    <li>
                        <input type="submit" name="assignment" value="assinatura">
                        <img src="../global/img/assinatura.png" alt="Pacotes">
                        <span>Pacotes</span>
                    </li>
                    <li>
                        <input type="submit" name="fly" value="blog">
                        <img src="icons/flight.png" alt="Voos Diretos">
                        <span>Blog</span>
                        </input>
                    </li>

                    <hr>
                </form>
            </ul>
        </nav>
    </header>
    <main>
        <section>

            <div class="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/banner1 (1).webp" alt="Imagem 1">
                    </div>
                    <div class="carousel-item">
                        <img src="img/drive.png" alt="Imagem 2">
                    </div>
                    <div class="carousel-item">
                        <img src="img/banner7.png" alt="Imagem 3">
                    </div>on>

                </div>
                <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
                <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <span class="indicator active" onclick="goToSlide(0)"></span>
                    <span class="indicator" onclick="goToSlide(1)"></span>
                    <span class="indicator" onclick="goToSlide(2)"></span>
                </div>
            </div>
        </section>

        <section>
            <div class="text-content">
                <h1>ExploraCar, os melhores preços estão aqui</h1>
                <p>Atendimento de qualidade e a melhor experiência em aluguel de carros com a ExploraCar</p>
            </div>
        </section>

        <section>
            <div class="container-destaques">
                <!-- Carousel 1 -->
                <div class="carousel-cars" data-carousel="1" id="carousel1">
                    <h1>Para toda sua família</h1>
                    <div class="carousel-track">
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Chevrolet/Chevrolet Spin.png" alt="Slide 1">
                            <h2>Chevrolet Spin</h2>
                            <p>O Chevrolet Spin é um monovolume espaçoso, com capacidade para até sete pessoas, oferecendo conforto, versatilidade e bom custo-benefício, sendo ideal para famílias maiores.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Fiat/Fiat Ducato.png" alt="Slide 2">
                            <h2>Fiat Ducato</h2>
                            <p>O Fiat Ducato é uma van de grande porte, ideal para transporte de mercadorias e passageiros. É um dos modelos preferidos para empresas que necessitam de um veículo utilitário de grande porte.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Nissan/Nissan Frontier.png" alt="Slide 3">
                            <h2> Nissan Frontier</h2>
                            <p>A Nissan Frontier é uma picape robusta e versátil, ideal para trabalho e aventuras off-road. Oferece potência, conforto e tecnologias modernas, garantindo bom desempenho em qualquer terreno.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Volkswagen/Volkswagen Saveiro.png"
                                alt="Slide 4">
                            <h2>Volkswagen Saveiro</h2>
                            <p>A Volkswagen Saveiro é uma picape compacta, ideal para quem precisa de um veículo de carga leve e versátil. A Saveiro é perfeita para uso urbano e pequenas tarefas profissionais.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Ford/Ford Explorer.png" alt="Slide 5">
                            <h2>Ford Explorer</h2>
                            <p>O Ford Explorer é um SUV grande, ideal para famílias ou viagens longas. Com interior espaçoso, motorização potente e tração integral, é perfeito tanto para a cidade quanto para terrenos desafiadores.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Citroen/Citroën Berlingo.png" alt="Slide 6">
                            <h2>Citroen Berlingo</h2>
                            <p>O Citroën Berlingo é um furgão prático e espaçoso, ideal para quem precisa de um veículo para transporte de carga ou viagens com a família. Ele combina funcionalidade com conforto, sendo uma ótima opção para o trabalho ou lazer.</p>
                        </div>
                    </div>
                    <div class="carousel-buttons">
                        <button class="anterior">&#10094;</button>
                        <button class="proximo">&#10095;</button>
                    </div>
                </div>

                <!-- Carousel 2 -->
                <div class="carousel-cars" data-carousel="2" id="carousel2">
                    <h1>Linha Black Friday</h1>
                    <div class="carousel-track">
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/Audi RS7.png" alt="Slide 1">
                            <h2>Audi RS7</h2>
                            <p>O Audi RS7 é um sedã esportivo de alto desempenho, com motor potente, design agressivo e tecnologias avançadas, proporcionando uma direção emocionante.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/Audi A8.png" alt="Slide 2">
                            <h2>Audi A8</h2>
                            <p>O Audi A8 é um sedã de luxo de grande porte, que combina conforto, desempenho e tecnologia avançada, sendo ideal para quem busca sofisticação em um carro de luxo.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/Lexus LX.png" alt="Slide 3">
                            <h2>Lexus LX</h2>
                            <p>O Lexus LX é um SUV de luxo de grande porte, interior luxuoso e capacidade para enfrentar qualquer terreno com conforto, ideal para quem busca um veículo imponente e versátil.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/Lexus LC.png" alt="Slide 4">
                            <h2>Lexus LC</h2>
                            <p>O Lexus LC é um coupé de luxo com motor potente e design impressionante, oferecendo desempenho e estética sofisticada para uma experiência de condução emocionante e estilosa.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/Volvo V90.png" alt="Slide 5">
                            <h2> Volvo V90</h2>
                            <p>O V90 é um station wagon de luxo, com grande capacidade de carga, interior luxuoso, desempenho refinado, tecnologia avançada e design elegante e prático.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Black Friday/BMW X6.png" alt="Slide 6">
                            <h2> BMW X6</h2>
                            <p>O BMW X6 é um SUV de luxo com design arrojado, motorização potente e performance excepcional, oferecendo uma condução emocionante com conforto e tecnologia.</p>
                        </div>
                    </div>
                    <div class="carousel-buttons">
                        <button class="anterior">&#10094;</button>
                        <button class="proximo">&#10095;</button>
                    </div>
                </div>

                <!-- Carousel 3 -->
                <div class="carousel-cars" data-carousel="3" id="carousel3">
                    <h1>Para quem busca conforto</h1>
                    <div class="carousel-track">
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Volkswagen/Jetta.png" alt="Slide 1">
                            <h2> Volkswagen Jetta</h2>
                            <p>O Volkswagen Jetta é um sedã médio elegante, interior espaçoso e tecnologia avançada. Seus motores potentes proporcionam uma condução prazerosa, sendo ideal para viagens e uso diário.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Toyota/Corolla.png" alt="Slide 2">
                            <h2> Toyota Corolla</h2>
                            <p>O Toyota Corolla é um sedã confiável e eficiente, com opções híbrida e a gasolina, oferecendo condução suave, baixo consumo e uma cabine equipada, ideal para uso diário ou familiar.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Hyundai/Hyundai i30.png" alt="Slide 3">
                            <h2> Hyundai i30</h2>
                            <p>O Hyundai i30 é um hatchback de médio porte, com bom desempenho, interior espaçoso e recursos de conectividade e segurança, sendo ideal para quem busca versatilidade, conforto e ótimo custo-benefício.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Honda/Honda HR-V.png" alt="Slide 4">
                            <h2> Honda HR-V</h2>
                            <p>O Honda HR-V é um SUV compacto, elegante e confortável. Tecnologia avançada e amplo espaço interno, sendo ideal para quem busca um carro prático e versátil.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Honda/Honda Civic.png" alt="Slide 5">
                            <h2> Honda Civic</h2>
                            <p>O Honda Civic é um sedan médio confiável, com design moderno e bom desempenho. Oferece motorização 1.5 turbo e 2.0, tecnologia avançada e recursos de segurança, sendo uma opção confortável e durável.</p>
                        </div>
                        <div class="carousel-cars-item"><img src="../Locação/img/img Carros/img Nissan/Nissan Sentra.png" alt="Slide 6">
                            <h2> Nissan Sentra</h2>
                            <p>O Nissan Sentra é um sedã médio que oferece conforto, design elegante e tecnologias de segurança, ideal para quem busca sofisticação e desempenho no dia a dia.</p>
                        </div>
                    </div>
                    <div class="carousel-buttons">
                        <button class="anterior">&#10094;</button>
                        <button class="proximo">&#10095;</button>
                    </div>
                </div>
            </div>
        </section>


        <div class="buttons">
            <div class="button-central">
                <a href="../Locação/veiculos.html">
                    <button>
                        <span></span>
                        Confira todas as opções!
                    </button>
                </a>
            </div>
        </div>
        </section>

        <aside>
            <a href="../Locação/veiculos.html">
                <div class="propaganda">

                </div>
            </a>
        </aside>


        <div class="container-vantagens">
            <h1>Vantagens de alugar com a ExploraCar</h1>
            <div class="advantages">
                <div class="advantage">
                    <i class="fas fa-dollar-sign"></i>
                    <h2>Melhor preço: até 30% OFF</h2>
                    <p>Encontrou uma tarifa menor depois de ter reservado com a ExploraCar? Nós reembolsamos a diferença!</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-credit-card"></i>
                    <h2>Reserve com sua preferência de pagamento</h2>
                    <p>Alugue um carro parcelando em até 12x no cartão de crédito, via PIX ou boleto!</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-globe"></i>
                    <h2>Compare os melhores preços do continente</h2>
                    <p>Esqueça pagamento em dólar ou outra moeda: para reservas internacionais feitas no Brasil, pague em reais e economize o IOF de 4,38%.</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-car"></i>
                    <h2>Aluguel de carro conveniente e fácil</h2>
                    <p>Compare preços de mais de 100 locadoras pelo Brasil em um só lugar, tenha o melhor atendimento e desfrute de promoções exclusivas!</p>
                </div>
            </div>
        </div>

        <div class="tittle-touch">
            <h1>Aluguel de carros por todo o Brasil</h1>
            <p>confira todas as regiões do Brasil que possuem nossas locadoras de carros</p>
        </div>
        <div class="container-touch">
            <div class="options">
                <div class="option active" style="--optionBackground:url(img/cristo-redentor-rio.jpg);">
                    <div class="content-container">
                        <div class="content-img">
                            <ul>
                                <li>São Paulo</li>
                                <li>Rio de Janeiro</li>
                                <li>Espirito Santo</li>
                                <li>Minas Gerais</li>
                            </ul>

                        </div>
                    </div>
                    <div class="shadow"></div>
                    <div class="label">
                        <div class="info">
                            <div class="main">Sudeste</div>
                        </div>
                    </div>
                </div>
                <div class="option" style="--optionBackground:url(img/Destinos-de-Inverno-Sul-do-Brasil.jpg);">
                    <div class="content-container">
                        <div class="content-img">
                            <ul>
                                <li>Paraná</li>
                                <li>Santa Catarina</li>
                                <li>Rio Grande do Sul</li>
                            </ul>

                        </div>
                    </div>
                    <div class="shadow"></div>
                    <div class="label">
                        <div class="info">
                            <div class="main">Sul</div>
                        </div>
                    </div>
                </div>
                <div class="option" style="--optionBackground:url(img/nordeeeetee.jpg);">
                    <div class="content-container">
                        <div class="content-img">
                            <ul>
                                <li>Pernambuco</li>
                                <li>Paraíba</li>
                                <li>Rio Grande do Norte</li>
                                <li>Alagoas</li>
                                <li>Ceará</li>
                            </ul>

                        </div>
                    </div>
                    <div class="shadow"></div>
                    <div class="label">
                        <div class="info">
                            <div class="main">Nordeste</div>
                        </div>
                    </div>
                </div>
                <div class="option" style="--optionBackground:url(img/norteee.webp);">
                    <div class="content-container">
                        <div class="content-img">
                            <ul>
                                <li>Piauí</li>
                                <li>Amazonas</li>
                                <li>Roraima</li>
                                <li>Amapá</li>

                            </ul>

                        </div>
                    </div>
                    <div class="shadow"></div>
                    <div class="label">
                        <div class="info">
                            <div class="main">Norte</div>
                        </div>
                    </div>
                </div>
                <div class="option" style="--optionBackground:url(img/centroOeste.jpg);">
                    <div class="content-container">
                        <div class="content-img">
                            <ul>
                                <li>Mato Grosso</li>
                                <li>Goiás</li>
                                <li>Mato Grosso do Sul</li>

                            </ul>

                        </div>
                    </div>
                    <div class="shadow"></div>
                    <div class="label">
                        <div class="info">
                            <div class="main">Centro-Oeste</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>








        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(".option").click(function() {
                $(".option").removeClass("active");
                $(this).addClass("active");

            });
        </script>
    </main>

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
                    <li><a href="index.php">Início</a></li>
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
    <script src="script.js"></script>
</body>

</html>