<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        const checkbox = document.getElementById('openmenu');
        const pai = document.querySelector('.pai');
        const body = document.body;

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                pai.style.filter = 'blur(32px)';
                body.classList.add('lock-scroll');
            } else {
                pai.style.filter = 'none';
                body.classList.remove('lock-scroll');
            }
        });
    </script>

    <script>
        window.onload = function () {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirma-senha');

            form.onsubmit = function (event) {
                event.preventDefault();


                if (!password.value || !confirmPassword.value) {
                    alert('Por favor, preencha os dois campos de senha.');
                    return;
                }


                if (password.value !== confirmPassword.value) {
                    alert('As senhas não coincidem.');
                    return;
                }

                alert('Login efetuado com sucesso!');
                form.submit();
                window.location.href = '../index.html';
            };
        };
    </script>

</head>

<body>
    <header>
        <div class="menu-container">
            <input type="checkbox" id="openmenu" class="hamburger-checkbox">
            <div class="hamburger-icon">
                <label for="openmenu" id="hamburger-label">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
            </div>
            <div class="menu-pane">
                <nav>
                    <ul class="menu-links" type="none">
                        <li><a href="../index.html">Home</a>
                        <li><a href="###" id="lista">Carros</a>
                            <ul class="submenu" type="none">
                                <li><a href="../modelos/conceptone.html">Concept One</a></li>
                                <li><a href="../modelos/nevera.html">Nevera</a></li>
                                <li><a href="../modelos/ctwo.html">Concept Two</a></li>
                            </ul>
                        </li>
                        <li><a href="cont.html">Contato</a></li>
                        <li><a href="../sobrenos/sobre.html">Sobre Nós</a></li>
                    </ul>
                </nav>
            </div>
    </header>
    <main class="pai">
        <div class="login">
            <h1>
                CRIE SUA CONTA NA RIMAC
            </h1>
            <br>
            <div class="area">
                <form action="#" method="post">
                    <label for="senha"> Digite seu nome </label>
                    <input type="text" id="name" name="nome" required placeholder="Seu nome"> <br><br>

                    <label for="email"> Digite seu Email</label>
                    <input type="email" id="email" name="email" required placeholder="Ex: Seunome1232@gmail.com">
                    <br><br>

                    <label for="senha">Coloque sua senha</label>
                    <input type="password" id="password" name="password" required placeholder="Ex: (FSJD24233)">
                    <br><br>

                    <label for="confirma-senha">Confirmar Senha</label>
                    <input type="password" id="confirma-senha" name="confirma-senha" required placeholder=""> <br><br>

                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" required placeholder="(00) 0000-0000"><br><br>
                    <div class="aceito">
                        <p>Eu li e concordo com os termos de uso </p>
                        <input type="checkbox" id="check" required>
                    </div>
                    <br>
                    <button class="botao" type="submit" name="Continuar">Continuar</button><br>
                </form>
            </div>

            <div class="footer">
                <p>
                    Esta página usa hCaptcha. Para mais detalhes, consulte a Proteção de Dados ou o Aviso Legal.
                </p>
                <div class="link">
                    <a href="#">
                        Informações Legais
                    </a>
                    <a href="#">
                        Política de Privacidade
                    </a>
                    <a href="#">
                        Modo de exibição
                    </a>
                </div>
            </div>
        </div>
        <div class="foto"></div>
    </main>

    <script>
        const telefone = document.querySelector('#telefone');
        telefone.addEventListener('keyup', () => {
            let valor = telefone.value.replace(/\D+/g, '').slice(0, 11);
            valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            telefone.value = valor;
        });
    </script>
</body>

</html>