<?php
require_once("arrays.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["nome"];
    $descricao = $_POST["descricao"];

    $novaNoticia = [
        "nome" => $titulo,
        "descricao" => $descricao,
        "imagem" => '',
        "data" => '2024-10-30'
    ];

    array_push($noticias, $novaNoticia);
    $_SESSION['noticias'] = $noticias;
}
// print "<pre>";
// print_r($_SERVER);
// print "</pre>";


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <title>Document</title>
    <style>
        body {
            /* display: flex; */
            flex-direction: row;
        }

        .card {
            width: 25%;
            display: flex;
            flex-direction: column;
            float: left;
            border-radius: 10px;
            box-shadow: 0 4 12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.5s ease;
            transform: scale(1);
            margin-top: 200px;
            justify-content: space-between;
            margin: 50px;
            padding: 30px;
            margin-left: 105px;
        }

        .card:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .card img {
            width: 100%;
            height: 450px;

        }

        .card a {
            font-weight: bold;
            font-size: 24px;
            text-decoration: none;
            color: black;
        }

        .card p {
            font-size: 12px;
            color: black;
        }

        .banner img {
            width: 100%;
            height: 70vh;
            object-fit: cover;
            margin-top: 90px;
        }
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
                            <img src="../global/img/blog.png" alt="Blog">
                            <span>Blog</span>
                        </button>
                    </li>
                    <hr>
                </form>

            </ul>
        </nav>
    </header>

    <div class="banner">
        <img src="../home/img/familia-viagem-carro-1091522177.jpg" alt="Banner">
    </div>






    <?php

    $count = 0;
    foreach ($noticias as $noticia) {
        print "
        <div class='card'>
        <a  href='noticia.php?index=" . $count++ . "'> <img src='imge/" . $noticia["imagem"] . "'>
        " . $noticia["nome"] . "</a>
        <p>" . $noticia["descricao"] . "</p>
        <p>" . $noticia["data"] . "</p>
        </div>
        ";
    }


    ?>

    <script src="../global/global.js"></script>
    <div style="height: 150vh;"></div>


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