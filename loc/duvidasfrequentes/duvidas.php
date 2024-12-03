<!DOCTYPE html>
<html lang="pt-BR">
<!-- Declaração do tipo de documento HTML e definição do idioma como português do Brasil -->

<head>
    <meta charset="UTF-8">
    <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Configura a visualização para dispositivos móveis -->
    <title>FAQ - Aluguel de Carros</title>
    <!-- Título da página exibido na aba do navegador -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link para o arquivo CSS da Font Awesome para ícones -->
    <link rel="stylesheet" href="duvidas.css">
    <!-- Link para o arquivo CSS externo -->

    <script>
        // Função para expandir ou colapsar respostas das FAQs
        function toggleAnswer(event) {
            const faqItem = event.currentTarget;
            const answer = faqItem.querySelector('p');
            const icon = faqItem.querySelector('.icon');
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
                icon.className = 'icon fas fa-chevron-down';
            } else {
                answer.style.display = 'block';
                icon.className = 'icon fas fa-chevron-up';
            }
        }

        // Função que será executada ao carregar o documento
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona o evento de clique em cada item FAQ
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => item.addEventListener('click', toggleAnswer));

            // Adiciona o evento de envio ao formulário
            document.getElementById('questionForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Impede o envio padrão do formulário

                const formData = new FormData(this); // Obtém os dados do formulário
                const messageDiv = document.getElementById('message'); // Seleciona o div de mensagem

                // Envia o formulário para enviar_pergunta.php usando AJAX
                fetch('enviar_pergunta.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Converte a resposta para JSON
                    .then(data => {
                        if (data.success) {
                            // Cria um novo item de FAQ para a pergunta enviada
                            const faqContainer = document.querySelector('.faq');
                            const newFaqItem = document.createElement('div');
                            newFaqItem.classList.add('faq-item');
                            newFaqItem.innerHTML = `<h2>${data.question} <i class="icon fas fa-chevron-down"></i></h2><p style="display: none;">Enviaremos sua pergunta para o nosso time de atendimento e responderemos via email em breve.</p>`;

                            // Adiciona o evento de clique ao novo item para expandir/colapsar
                            newFaqItem.addEventListener('click', toggleAnswer);

                            // Adiciona o novo item ao container de FAQs
                            faqContainer.appendChild(newFaqItem);

                            // Exibe uma mensagem de sucesso
                            messageDiv.textContent = "Obrigado por enviar sua pergunta!";
                            messageDiv.style.color = 'green';

                            // Limpa o campo de pergunta
                            document.getElementById('question').value = '';
                        } else {
                            // Exibe a mensagem de erro
                            messageDiv.textContent = data.message;
                            messageDiv.style.color = 'red';
                        }
                    })
                    .catch(error => {
                        // Exibe uma mensagem de erro em caso de falha
                        messageDiv.textContent = 'Erro ao enviar a pergunta. Tente novamente mais tarde.';
                        messageDiv.style.color = 'red';
                    });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Perguntas Frequentes</h1>
        <!-- Título principal da página de Perguntas Frequentes -->
        <div class="faq">
            <?php
            $faqs = [
                ["Socorro! Preciso alterar/cancelar minha reserva!", "Você pode alterar ou cancelar sua reserva acessando a página de reservas no site."],
                ["Onde está a minha reserva de aluguel de carro?", "Você pode encontrar sua reserva entrando no seu perfil e acessando 'Minhas Reservas'."],
                ["Qual é a política de reembolso da ExploraCar?", "A política de reembolso varia dependendo do provedor de serviços. Consulte os detalhes na sua confirmação de reserva."],
                ["Quando clico em uma oferta no seu site, aparece uma mensagem dizendo que o preço subiu. Por que isso acontece?", "Os preços de aluguel de carros podem mudar rapidamente devido à demanda e disponibilidade."],
                ["Quais são os requisitos de idade para alugar um carro?", "A idade mínima para alugar um carro é geralmente 21 anos, mas pode variar dependendo da locadora e do país."],
            ];

            foreach ($faqs as $faq) {
                echo "<div class='faq-item'>";
                echo "<h2>{$faq[0]} <i class='icon fas fa-chevron-down'></i></h2>";
                echo "<p>{$faq[1]}</p>";
                echo "</div>";
            }
            ?>
        </div>
        <!-- Seção de perguntas frequentes gerada dinamicamente com PHP -->
    </div>

    <div class="miniContainer">
        <div class="question-container">
            <h2>Envie sua Pergunta</h2>
            <!-- Título da seção de envio de perguntas -->
            <form id="questionForm">
                <textarea id="question" name="question" rows="4" placeholder="Escreva sua pergunta aqui..."></textarea>
                <!-- Campo de texto para o usuário escrever sua pergunta -->
                <button type="submit" class="submit-button">Enviar</button>
                <!-- Botão de envio do formulário -->
            </form>
            <div id="message" style="margin-top: 10px;"></div> <!-- Div para exibir mensagens -->
        </div>
    </div>

    <div class="cont-mapa">
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d913.7161618365983!2d-46.557862029711565!3d-23.64501788526892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-BR!2sbr!4v1732042109592!5m2!1spt-BR!2sbr"
                width="50"
                height="10"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!-- Mapa do Google embutido -->
        </div>

        <div class="cont-tm">
            <!-- Informações de Contato -->
            <h2>Entre em Contato Conosco</h2>
            <div class="contact-info">
                <div class="two-column">
                    <div class="info-item" id="local">
                        <i class="fas fa-map-marker-alt icon"></i> <br>
                        <p><strong>Local:</strong> <br> Rua Boa Vista, 180, São Caetano, SP</p>
                        <!-- Localização da empresa -->
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock icon"></i> <br>
                        <p><strong>Horários de Atendimento:</strong> <br> 08:00 am - 17:00 pm</p>
                        <!-- Horários de atendimento -->
                    </div>
                </div>
                <div class="two-column" id="two2">
                    <div class="info-item">
                        <i class="fas fa-phone icon"></i> <br>
                        <p><strong>Ligue:</strong> <br>+55 (11) 9933-4587</p>
                        <!-- Número de telefone para contato -->
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope icon"></i> <br>
                        <p><strong>Escreva:</strong> <br> contatoexploracar@gmail.com</p>
                        <!-- Endereço de e-mail para contato -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
