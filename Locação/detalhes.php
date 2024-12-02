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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de carros - ExploraCar</title>
    <link rel="stylesheet" href="@import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Oswald:wght@200..700&display=swap');">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="./detalhes.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <!-- Link para o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    tem menu de contexto

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
            <img src="icons/flight.png" alt="Blog">
            <span>Blog</span>
        </button>
    </li>
    <hr>
</form>

            </ul>
        </nav>
    </header>

    <main>
        <div class="detail-container">
            <img id="vehicle-img" src="" alt="Imagem do Veículo">
            <div class="detail-info">
                <h2 id="vehicle-title"></h2> <span id="border"></span>
                <p> <i class="fas fa-car-side"></i> Número de portas: <span id="vehicle-doors"></span></p>
                <p> <i class="fas fa-car-battery"></i> Nome do motor: <span id="vehicle-engine"></span></p>
                <p> <i class="fas fa-truck"></i> Câmbio: <span id="vehicle-cambio"></span></p>
                <p> <i class="fas fa-thermometer-empty"></i> Ar Condicionado: <span id="vehicle-arcondicionado"></span></p>
                <p> <i class="fas fa-gas-pump"></i> Política de combustivel: <span id="vehicle-combustivel"></span></p>
                <p> <i class="fas fa-car"></i> <span id="vehicle-quilometragem"></span></p>
                <p> <i class="fas fa-shield-alt"></i><span id="vehicle-seguro"></span></p>
                <div class="card-price"> <span id="border2"></span>
                    <p id="vehicle-descontPrice"><span id="descontPrice"></span></p>
                    <p id="vehicle-price"><span id="price"></span></p>
                    <p id="vehicle-diaria"><span id="diaria"></span> por dia</p>
                    
                    
                    <form action="../pagamento/pagamento.php" method="POST">                        
                        <input type="hidden" name="id" value="<?php echo $pacote['id']; ?>">
                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($pacote['title']); ?>">
                        <input type="hidden" name="desc" value="<?php echo htmlspecialchars($pacote['desc']); ?>">
                        <!-- <input type="hidden" name="price" value="<?php echo $pacote['price']; ?>"> -->
                        <!-- <input type="hidden" name="img" value=""> -->
                        <!-- <input type="hidden" name="descontPrice" value=""> -->
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars(number_format($pacote['price'], 2, '.', '')); ?>">
                        <!-- <input type="hidden" name="diaria" value=""> -->
                        <div class="buttons" id="buttons"><button type="submit" class="reservar" onclick="reservar()"><span></span>Reservar</button></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="parteBaixo">
            <div class="deta">
                <div class="card-carrosel">
                    <div class="carrossel-container">
                        <div class="carrossel">
                            <img src="../home/img/banner2.png" alt="Imagem 1">
                            <img src="../home/img/banner3.webp" alt="Imagem 2">
                            <img src="../home/img/banner4.png" alt="Imagem 3">
                        </div>
                    </div>
                    <div class="nav-buttons">
                        <button id="prev" class="prev-button">←</button>
                        <button id="next" class="next-button">→</button>
                    </div>
                </div>
                <div class="container-especification">
                    <div class="rental-policy">
                        <h3 class="titulo">Política de Aluguel</h3>
                        <h4 class="titulo">Política de cancelamento e no show</h4>
                        <p>Cancelamento gratuito disponível até o momento da retirada.</p>
                        <h4 class="titulo">Sobretaxa por idade</h4>
                        <p>Pode ser aplicada a motoristas com menos de 25 e mais de 70 anos de idade.</p>
                        <h4 class="titulo">Oque inclui?</h4>
                        <p>Os itens abaixo estão incluídos no preço total do aluguel do carro.</p>
                        <h4 class="titulo">Proteção básica contra danos por colisão</h4>
                        <p>O preço do seu aluguel inclui proteção básica contra danos por colisão, que cobre danos à carroceria ou roubo do veículo alugado. A restrição de responsabilidade em caso de danos por colisão não inclui danos ou perdas de pneus, para-brisas, janelas ou chassi. A locadora de carros pode determinar a aplicação de uma franquia.</p>
                    </div>
                    <div class="location-pickup">
                        <h3 class="titulo">Locais de retirada e devolução</h3>
                        <h4 class="titulo">Retirada e devolução</h4>
                        <p class="info"> <i class="fas fa-calendar-alt"></i> ter., 10 de dez., - ter., 17 de dez., 12h00</p>
                        <p class="info"> <i class="fas fa-map-marker-alt"></i> Rua Boa Vista, 825, São Caetano, SP</p>
                        <p class="info"> <i class="fas fa-clock"></i> Horário de funcionamento das 7h30 - 18h00</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-performance">
            <div class="title">PERFORMANCE</div>
            <div class="features">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <h3>SEGURANÇA</h3>
                    <p>7 airbags, sensores de estacionamento dianteiro e traseiro e TSS<sup>4</sup> (Toyota Safety...</p>
                </div>
                <div class="feature">
                    <i class="fas fa-microchip"></i>
                    <h3>TECNOLOGIA</h3>
                    <p>Ar-condicionado digital automático, central multimídia de 9" com conexão Android Auto® e...</p>
                </div>
                <div class="feature">
                    <i class="fas fa-tachometer-alt"></i>
                    <h3>PERFORMANCE</h3>
                    <p>Motor de 2.0 L Dual VVT-iE 16 V DOHC Flex com potência de 175 cv (E) / 167 cv (G)<sup>5</sup> e 21,3 Kgf.m de...</p>
                </div>
                <div class="feature">
                    <i class="fas fa-car-side"></i>
                    <h3>DESIGN</h3>
                    <p>Rodas de liga leve 16" e retrovisores externos elétricos na cor do carro com pisca integrado...</p>
                </div>
            </div>
        </div>
    </main>


    <script>
        // esse aqui faz aparecer as informaçoes dos cards.
        document.addEventListener('DOMContentLoaded', function() {
            const vehicle = JSON.parse(localStorage.getItem('selectedVehicle'));
            if (vehicle) {
                document.getElementById('vehicle-img').src = vehicle.img;
                document.getElementById('vehicle-title').textContent = vehicle.title;
                document.getElementById('vehicle-doors').textContent = vehicle.doors;
                document.getElementById('vehicle-engine').textContent = vehicle.engine;
                document.getElementById('vehicle-cambio').textContent = vehicle.cambio;
                document.getElementById('vehicle-arcondicionado').textContent = vehicle.arcondicionado;
                document.getElementById('vehicle-combustivel').textContent = vehicle.combustivel;
                document.getElementById('vehicle-quilometragem').textContent = vehicle.quilometragem;
                document.getElementById('vehicle-seguro').textContent = vehicle.seguro;
                document.getElementById('descontPrice').textContent = vehicle.descontPrice;
                document.getElementById('price').textContent = vehicle.price;
                document.getElementById('diaria').textContent = vehicle.diaria;
            }
        });
        // esse aqui é responsável pelo botao de reservar e os preços.
        function reservar() {
            const vehicle = JSON.parse(localStorage.getItem('selectedVehicle'));
            if (vehicle) {
                document.querySelector('input[name="title"]').value = vehicle.title;
                document.querySelector('input[name="desc"]').value = vehicle.desc;
                document.querySelector('input[name="id"]').value = vehicle.id;
                // document.querySelector('input[name="img"]').value = vehicle.img;
                // document.querySelector('input[name="descontPrice"]').value = vehicle.descontPrice;
                document.querySelector('input[name="price"]').value = vehicle.price;
                // document.querySelector('input[name="diaria"]').value = vehicle.diaria;
            }
        }
    </script>

    <script>
        // carrosel das imagens de banners
        const carrossel = document.querySelector('.carrossel');
        const images = document.querySelectorAll('.carrossel img');
        const prevButton = document.getElementById('prev');
        const nextButton = document.getElementById('next');
        let idx = 0;

        function showImage(index) {
            carrossel.style.transform = `translateX(${-index * 100 / images.length}%)`;
        }

        prevButton.addEventListener('click', () => {
            idx = idx <= 0 ? images.length - 1 : idx - 1;
            showImage(idx);
        });

        nextButton.addEventListener('click', () => {
            idx = idx >= images.length - 1 ? 0 : idx + 1;
            showImage(idx);
        });
    </script>

    <script src="../global/global.js"></script>
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
</body>

</html>