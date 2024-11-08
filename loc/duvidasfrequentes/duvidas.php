<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Aluguel de Carros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .faq {
            margin: 20px 0;
        }
        .faq-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 15px;
            cursor: pointer;
            position: relative;
        }
        .faq-item h2 {
            margin: 0;
            font-size: 1.2em;
            color: #007BFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-item p {
            display: none;
            margin-top: 10px;
            color: #555;
        }
        .icon {
            font-size: 1.5em;
            color: #007BFF;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
    <script>
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
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => item.addEventListener('click', toggleAnswer));
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Carros</h1>
        <div class="faq">
            <?php
                $faqs = [
                    ["Socorro! Preciso alterar/cancelar minha reserva!", "Você pode alterar ou cancelar sua reserva acessando a página de reservas no site."],
                    ["Onde está a minha reserva de aluguel de carro?", "Você pode encontrar sua reserva entrando no seu perfil e acessando 'Minhas Reservas'."],
                    ["Qual é a política de reembolso do KAYAK?", "A política de reembolso varia dependendo do provedor de serviços. Consulte os detalhes na sua confirmação de reserva."],
                    ["Quando clico em uma oferta no seu site, aparece uma mensagem dizendo que o preço subiu. Por que isso acontece?", "Os preços de aluguel de carros podem mudar rapidamente devido à demanda e disponibilidade."],
                    ["Quais são os requisitos de idade para alugar um carro?", "A idade mínima para alugar um carro é geralmente 21 anos, mas pode variar dependendo da locadora e do país."]
                ];

                foreach ($faqs as $faq) {
                    echo "<div class='faq-item'>";
                    echo "<h2>{$faq[0]} <i class='icon fas fa-chevron-down'></i></h2>";
                    echo "<p>{$faq[1]}</p>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
</body>
</html>
