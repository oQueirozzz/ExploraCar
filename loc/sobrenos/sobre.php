<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre nós - ExploraCar</title>
    <link rel="stylesheet" href="../../global/global.css">
    <link rel="stylesheet" href="sobre.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <script src="../../global/global.js"></script>

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
                        <button id="principal-button" class="btn" onclick="toggleLogoutTab()">
                            <img src="../../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="<?= htmlspecialchars($_SESSION['nome']); ?>"> </p>
                            <div class="seta"></div>
                        </button>

                        <button id="help-button" class="btn" onclick="toggleHelpTab()">
                            <img src="../../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="AJUDA"> </p>
                            <div class="seta"></div>
                        </button>




                    <?php else: ?>
                        <!-- Botão padrão "ENTRAR" -->

                        <button id="principal-button" class="btn" onclick="toggleInfoTab()">
                            <img src="../../global/img/file.png" alt="">
                            <span></span>
                            <p data-start="good luck!" data-text="start!" data-title="ENTRAR"> </p>
                            <div class="seta"></div>
                            <!-- <img id="seta" src="img/seta.png" alt=""> -->
                        </button>
                        </a>
                        <button id="help-button" class="btn" onclick="toggleHelpTab()">
                            <img src="../../global/img/file.png" alt="">
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
                <li><a href="../Locação/veiculos.html"><img src="../../global/img/carroICON.jpg" alt="veiculos"
                            id="transparent">
                        <span>Carros</span></a>
                </li>
                <li><a href="#"><img src="../../global/img/sobre.png" alt="Sobre"> </a><span>Sobre Nós</span></li>
                <li><a href="../assinatura/assinatura.html"><img src="../../global/img/assinatura.png" alt="Pacotes">
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

    <div class="banner">

        <h1 class="text-title">Sua Viagem Começa Aqui.</h1>

    </div>

    <div class="conheca">
        <h2 class="title-conheca">Conheça a história da ExploraCar</h2>
        <p class="text-conheca">
            A ExploraCar nasceu com o propósito de oferecer uma experiência de locação de veículos simples, confiável e inovadora. Fundada por um grupo de entusiastas de mobilidade, a empresa começou com uma frota pequena, mas focada em qualidade e segurança.
            Com o tempo, a ExploraCar se destacou por investir em tecnologia, criando uma plataforma online fácil de usar e garantindo atendimento ágil e transparente. A empresa também adotou práticas sustentáveis, com carros híbridos e elétricos, comprometendo-se com o futuro e a redução de impacto ambiental.
            Hoje, a ExploraCar é sinônimo de confiança e flexibilidade, oferecendo soluções personalizadas para viagens de negócios, lazer e necessidades do dia a dia, sempre com foco na satisfação do cliente e inovação constante</p>
    </div>

    <div class="image">
        <h1 class="title-image">Aluguel de carros por todo o Brasil</h1>
        <img src="./img/viagem.webp" alt="viagem" class="img-viagem">
    </div>

    <section>

        <video autoplay="on" class="bg_video" loop muted poster="imagens/poster.jpg">
            <source src="./img/videoexploracar.mp4" type="video/mp4" autoplay muted loop>
        </video>
    </section>

    <div class="sep-color">
        <div class="icons">

            <div class="mission">
                <img src="./img/alvo.png" alt="missão">
                <h2 class="title-icon">Missão</h2>
                <p class="text-mvv">"Oferecer soluções de locação de veículos com qualidade, <br>
                    facilidade e preços justos, garantindo segurança e conforto. <br>
                    Nossa missão é ajudar as pessoas a explorarem o mundo <br>
                    com liberdade e praticidade."</p>
            </div>

            <div class="vision">
                <img src="./img/lampada.png" alt="visão">
                <h2 class="title-icon">Visão</h2>
                <p class="text-mvv">"Ser a principal referência em locação de veículos, <br>
                    oferecendo inovação, qualidade e sustentabilidade. <br>
                    Queremos facilitar o transporte, transformar jornadas em experiências <br>
                    e ser a primeira escolha de quem busca liberdade e conveniência."</p>
            </div>

            <div class="values">
                <img src="./img/valor.png" alt="valores" id="values">
                <h2 class="title-icon" id="valores">Valores</h2>
                <p class="text-mvv">
                    Agimos com confiança, transparência e compromisso, priorizando a qualidade, segurança e inovação na nossa frota. Valorizamos a sustentabilidade e oferecemos atendimento ágil e personalizado, com soluções flexíveis para atender às necessidades dos nossos clientes.
            </div>
        </div>
    </div>

</body>

</html>