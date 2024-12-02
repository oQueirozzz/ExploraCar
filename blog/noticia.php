<?php
require_once("arrays.php");
$index = $_GET["index"];
$noticia = $noticias[$index];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../global/global.css">
    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f5f5f5;
        }
        img{
            width: 100vw;
            height: 700px;
            margin-top: 50px;
            object-fit: cover;
        }
        h1{
            margin-top: 50px;
            font-size: 50px;
        }
        p{
            margin: 100px 100px 0px 100px;
            font-size: 20px;
            text-align: justify;
        }
        header {
            width: 100vw;
        }
        nav {
            background-color: #171a4a;
            width: 100%;
            height: 100px;
            color: white;
            display: flex;
            justify-content: space-evenly;
            
        }
        nav img{
            width: 200px;
            height: 100px;
            margin-right: 600px;
            transform: translateY(-50px);

        }

        nav ul {
            display: flex;
            width: 400px;
        }


        .menu {
            width: 250px;
            display: flex;
            flex-direction: column;
            margin: 20px;
            float: left;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.5s ease;
            transform: scale(1);
            margin: auto;

        }

        .menu:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

        }

        .menu a {
            text-decoration: none;
            color: white;
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
            <img src="icons/flight.png" alt="Blog">
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
"<h1>" . $noticia["nome"] . "</h1>
<img src='imge/" . $noticia["imagem"] . "'>
<p>" . $noticia["main"] . "</p>
<p>" . $noticia["data"] . "</p>"
?>
    
</body>
</html>
