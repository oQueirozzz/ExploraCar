<?php
session_start();

// Verifica se os dados estão na sessão
if (isset($_SESSION['pagamento'])) {
    $dados = $_SESSION['pagamento'];

    // Exibe os dados
    echo "<h1>Histórico de Pagamento</h1>";
    echo "<p>ID: {$dados['id']}</p>";
    echo "<p>Nome: {$dados['nome']}</p>";
    echo "<p>Descrição: {$dados['descricao']}</p>";
    echo "<p>Valor: R$ {$dados['valor']}</p>";

    // Limpa os dados da sessão
    unset($_SESSION['pagamento']);
} else {
    echo "<p>Nenhuma informação de pagamento encontrada.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - ExploraCar</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="historico.css">
</head>
<body>
<header>
        <div class="cabecalho">
            <div id="menu-toggle" onclick="toggleMenu()">
                <i class="material-symbols-outlined ">menu</i>
            </div>

            <a href="../home/index.php">
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
                <li><a href="../Locação/veiculos.html"><img src="../global/img/carroICON.jpg" alt="veiculos"
                            id="transparent">
                        <span>Carros</span></a>
                </li>
                <li><a href="#"><img src="../global/img/sobre.png" alt="Sobre"> </a><span>Sobre Nós</span></li>
                <li><a href="../assinatura/assinatura.html"><img src="../global/img/assinatura.png" alt="Pacotes">
                        <span>Pacotes</span></a></li>
                <hr>
                <li><img src="icons/flight.png" alt="Voos Diretos"> <span>Blog</span></li>
                <li><img src="icons/clock.png" alt="Melhor Momento"> <span>Suas reservas</span></li>
                <hr>
            </ul>
        </nav>
    </header> 

    <main>
    <h1>Histórico de Carros Comprados</h1>

        <div class="card-container">
            <div class="card">
                <img src="https://via.placeholder.com/300x150" alt="Honda Civic 2022">
                <div class="card-content">
                    <h2>Honda Civic 2022</h2>
                    <p>Data de Compra: <span>15/03/2023</span></p>
                    <p>Preço: <span>R$ 120.000,00</span></p>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300x150" alt="Toyota Corolla 2021">
                <div class="card-content">
                    <h2>Toyota Corolla 2021</h2>
                    <p>Data de Compra: <span>20/07/2022</span></p>
                    <p>Preço: <span>R$ 110.000,00</span></p>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300x150" alt="Ford Focus 2020">
                <div class="card-content">
                    <h2>Ford Focus 2020</h2>
                    <p>Data de Compra: <span>05/11/2021</span></p>
                    <p>Preço: <span>R$ 85.000,00</span></p>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300x150" alt="Chevrolet Cruze 2019">
                <div class="card-content">
                    <h2>Chevrolet Cruze 2019</h2>
                    <p>Data de Compra: <span>10/02/2020</span></p>
                    <p>Preço: <span>R$ 70.000,00</span></p>
                </div>
            </div>
        </div>

        <script>
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
                                <button class="reservar" onclick="reserveVehicle(${i}, '${type}')">Reservar</button>
                            </div>
                        `;
                        cardContainer.appendChild(newCard);
                    }
                    cardsGenerated[type] = true;
                }
                setActiveButton(button); // Define o botão como ativo
            }
    </script>

        <section class="none-wallet">
            <h3>Nenhuma marca selecionada</h3>
            <p>Por favor selecione um dos botões acima para continuar.</p>
            <img src="./img/CarroPretoBranco.png">
        </section>
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
                <li><a href="#">Termos de uso</a></li>
                <li><a href="../loc/politicas/politicas.html">Política de Privacidade</a></li>
                <li><a href="#">LGPD</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>