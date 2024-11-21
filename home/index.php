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
        echo "Lendo linha: $linha<br>"; // Debug

        // Remove espaços e quebras de linha
        $dados = explode(",", trim($linha));

        // Verifica se o número de campos está correto
        if (count($dados) < 8) {
            echo "Erro: Linha com dados incompletos.<br>";
            continue;
        }

        list($cpf, $nome, $sobrenome, $emailArquivo, $pais, $dataNascimento, $telefone, $senhaArquivo) = $dados;

        // Exibe os dados para debug
        echo "Email no arquivo: $emailArquivo, Senha no arquivo: $senhaArquivo<br>";

        // Verifica o email e a senha
        if ($email === $emailArquivo && $senha === $senhaArquivo) {
            fclose($handle);

            // Salva o nome completo na sessão
            $_SESSION['nome'] = $nome . ' ' . $sobrenome;
            return true;
        }
    }

    fclose($handle); // Fecha o arquivo
    return false; // Se não encontrou o usuário
}

// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Debug dos valores recebidos
    var_dump($email, $senha);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (verificarLogin($email, $senha)) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php"); // Redireciona para a página inicial
        exit;
    } else {
        $erro = "Email ou senha incorretos.";
    }
}

if (trim($email) === trim($emailArquivo) && trim($senha) === trim($senhaArquivo)) {
    fclose($handle);
    $_SESSION['nome'] = $nome . ' ' . $sobrenome;
    return true;
}
echo $mensagem;
echo $erro;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Lateral Expansível</title>
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

                    <div class="buttons">
                        <div class="dropdown">
                            <?php if (isset($_SESSION['nome'])): ?>
                                <!-- Botão com o nome do usuário logado -->
                                <button id="principal-button" class="btn"  onclick="toggleLogoutTab()">
                                    <img src="../global/img/file.png" alt="">
                                    <span></span>
                                    <p data-start="good luck!" data-text="start!" data-title="<?= htmlspecialchars($_SESSION['nome']); ?>"> </p>
                                    <div class="seta"></div>
                                </button>

                                <button id="help-button" class="btn" onclick="toggleHelpTab()">
                            <img src="../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p>
                            <div class="seta"></div>
                        </button>

                                


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
                        <button id="help-button" class="btn" onclick="toggleHelpTab()">
                            <img src="../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p>
                            <div class="seta"></div>
                        </button>
                        <?php endif; ?>
                    </div>

                    <div id="logout-tab" class="logout-tab">
                        <div class="logout-content">
                            <div class="buttons"><a href="logout.php"><button>Sair</button"></a></div>
                            <span class="close-btn" onclick="toggleLogoutTab()">&times;</span>
                        </div>
                    </div>
                    
                    <div id="help-tab" class="help-tab">
                        <div class="help-content">
                            <span class="close-btn" onclick="toggleHelpTab()">&times;</span>
                            <div class="buttons">
                                <a href="../loc/duvidasfrequentes/duvidas.php">
                                    <button>
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
                                    <input type="password" id="password" name="password" required>

                                    <a href="../loc/form/esqueceusenha/esqueceusenha.html" class="esqueci">Esqueci minha senha</a>

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

            <!-- <a href="#"><button>Entrar</button></a> -->
            <!-- </div> -->
            <!-- </div> -->
        </div>
        <nav id="sidebar">
            <ul class="menu">
                <li><a href="../Locação/veiculos.html"><img src="../global/img/carroICON.jpg" alt="veiculos"
                            id="transparent">
                        <span>Carros</span></a>
                </li>
                <li><a href="#"><img src="../global/img/sobre.png" alt="Sobre"> </a><span>Sobre Nós</span></li>
                <li><a href="../assinatura/assinatura.html"><img src="../global/img/assinatura.png" alt="Pacotes">
                        <span>Pacotes de assinaturas</span></a></li>
                <hr>
                <li><img src="icons/flight.png" alt="Voos Diretos"> <span>Voos diretos</span></li>
                <li><img src="icons/clock.png" alt="Melhor Momento"> <span>Melhor momento</span></li>
                <hr>
                <li><img src="icons/briefcase.png" alt="Business"> <span>KAYAK for Business</span></li>
                <li><img src="icons/heart.png" alt="Trips"> <span>Trips</span></li>
                <hr>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <!-- <div class="banner">
                <img src="img/DALL·E 2024-11-01 14.59.27 - A realistic, low-saturation banner image for a car rental website, featuring a modern, sleek car on a scenic open road with mountains and clear skies .webp"
                    alt="">
                <p id="p1">Pesquise. Compare. Alugue.</p>
                <p id="p2"> Aluguel de carros com os melhores preços</p>
                <div class="atributions">
                    <div class="att1">
                        <img src="img/fileCheck.png" alt="check">
                        <p>Cancelamento grátis na maioria das reservas</p>
                    </div>
                    <div class="att2">
                        <img src="img/fileCheck.png" alt="check">
                        <p>Melhor preço garantido</p>
                    </div>
                    <div class="att3">
                        <img src="img/fileCheck.png" alt="check">
                        <p>Pague em reais sem IOF</p>
                    </div>
                </div>

            </div> -->
            <div class="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/banner1 (1).webp" alt="Imagem 1">
                    </div>
                    <div class="carousel-item">
                        <img src="img/ban4.png" alt="Imagem 2">
                    </div>
                    <div class="carousel-item">
                        <img src="img/banner3.webp" alt="Imagem 3">
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
            <div class="buttons">
                <a href="../Locação/veiculos.html">
                    <button>
                        <span></span>
                        Quero alugar agora!
                    </button>
                </a>
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
                    <p>Encontrou uma tarifa menor depois de ter reservado com a Rentcars? Nós reembolsamos a diferença!</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-credit-card"></i>
                    <h2>Reservas com até 10% de cashback</h2>
                    <p>Alugue um carro parcelando em até 12x no cartão de crédito, via PIX ou boleto. Ainda ganhe Cashback de até 10%!</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-globe"></i>
                    <h2>Compare os melhores preços de mais de 160 países</h2>
                    <p>Esqueça pagamento em dólar ou outra moeda: para reservas internacionais feitas no Brasil, pague em reais e economize o IOF de 4,38%.</p>
                </div>
                <div class="advantage">
                    <i class="fas fa-car"></i>
                    <h2>Aluguel de carro conveniente e fácil</h2>
                    <p>Compare preços de mais de 300 locadoras pelo mundo em um só lugar, tenha o melhor atendimento e desfrute de promoções exclusivas!</p>
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
        <script src="https://codepen.io/z-/pen/a8e37caf2a04602ea5815e5acedab458.js"></script>
        <script src="teste05.js"></script>
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
                    <li><a href="sobre.php">Sobre Nós</a></li>
                    <li><a href="carros.php">Carros Disponíveis</a></li>
                    <li><a href="contato.php">Contato</a></li>
                    <li><a href="termos.php">Termos e Condições</a></li>
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
                <li><a href="termos.php">Termos de uso</a></li>
                <li><a href="politica-privacidade.php">Política de Privacidade</a></li>
                <li><a href="lgpd.php">LGPD</a></li>
            </ul>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../global/global.js"></script>
    <script>
        function toggleInfoTab() {
            const infoTab = document.getElementById('info-tab');
            infoTab.style.display = infoTab.style.display === 'block' ? 'none' : 'block';
        }

        function toggleHelpTab() {
            const helpTab = document.getElementById('help-tab');
            helpTab.style.display = helpTab.style.display === 'block' ? 'none' : 'block';
        }
        function toggleLogoutTab() {
            const logoutTab = document.getElementById('logout-tab');
            logoutTab.style.display = logoutTab.style.display === 'block' ? 'none' : 'block';
        }
    </script>
    <script>
        let currentIndex = 0;

        function showSlide(index) {
            const slides = document.querySelectorAll('.carousel-item');
            const indicators = document.querySelectorAll('.indicator');

            // Corrige o índice se estiver fora dos limites
            if (index >= slides.length) currentIndex = 0;
            if (index < 0) currentIndex = slides.length - 1;

            const offset = -currentIndex * 100;
            document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;

            // Remover a classe 'active' de todos os indicadores e aplicar apenas ao atual
            indicators.forEach((indicator, i) => {
                if (i === currentIndex) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            currentIndex++;
            showSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex--;
            showSlide(currentIndex);
        }

        function goToSlide(index) {
            currentIndex = index;
            showSlide(currentIndex);
        }
    </script>
</body>

</html>