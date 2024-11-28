<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../loc/form/form.php");
    exit;
}

// Validação dos dados recebidos
$id = isset($_POST['id']) ? $_POST['id'] : null;
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
$valor = isset($_POST['valor']) ? $_POST['valor'] : null;

// Verifique se todos os dados foram enviados
if ($id && $nome && $descricao && $valor):
?>
<!DOCTYPE html>
<html lang="pt-br" class="hydrated">
<head>
    <meta charset="UTF-8">
    <style data-styles="">
        ion-icon {
            visibility: hidden
        }

        .hydrated {
            visibility: inherit
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="pagamento.css">
    <script type="module" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&amp;display=swap" rel="stylesheet">


    <title>Pagamento - ExploraCar</title>
    <style type="text/css" id="operaUserStyle"></style>
</head>

<body>
    <div class="container">
        <div class="payment">

            <div class="payment__shadow-dots"></div>
            <div class="payment__dots">
                <svg width="65" height="115" viewBox="0 0 65 115" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="17.5" cy="2.5" r="2.5" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="2.5" r="2.5" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="2.5" r="2.5" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="2.5" r="2.5" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="12.5" r="2.5" transform="rotate(90 17.5 12.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="12.5" r="2.5" transform="rotate(90 32.5 12.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="12.5" r="2.5" transform="rotate(90 47.5 12.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="42.5" r="2.5" transform="rotate(90 17.5 42.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="62.5" r="2.5" transform="rotate(90 47.5 62.5)" fill="#E0ECF7"></circle>
                    <circle cx="62.5" cy="2.5" r="2.5" transform="rotate(90 62.5 2.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="82.5" r="2.5" transform="rotate(90 47.5 82.5)" fill="#E0ECF7"></circle>
                    <circle cx="62.5" cy="22.5" r="2.5" transform="rotate(90 62.5 22.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="42.5" r="2.5" transform="rotate(90 47.5 42.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="42.5" r="2.5" transform="rotate(90 32.5 42.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="62.5" r="2.5" transform="rotate(90 32.5 62.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="22.5" r="2.5" transform="rotate(90 17.5 22.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="22.5" r="2.5" transform="rotate(90 32.5 22.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="22.5" r="2.5" transform="rotate(90 47.5 22.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="52.5" r="2.5" transform="rotate(90 17.5 52.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="72.5" r="2.5" transform="rotate(90 17.5 72.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="72.5" r="2.5" transform="rotate(90 47.5 72.5)" fill="#E0ECF7"></circle>
                    <circle cx="62.5" cy="12.5" r="2.5" transform="rotate(90 62.5 12.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="92.5" r="2.5" transform="rotate(90 47.5 92.5)" fill="#E0ECF7"></circle>
                    <circle cx="62.5" cy="32.5" r="2.5" transform="rotate(90 62.5 32.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="52.5" r="2.5" transform="rotate(90 47.5 52.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="52.5" r="2.5" transform="rotate(90 32.5 52.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="32.5" r="2.5" transform="rotate(90 17.5 32.5)" fill="#E0ECF7"></circle>
                    <circle cx="32.5" cy="32.5" r="2.5" transform="rotate(90 32.5 32.5)" fill="#E0ECF7"></circle>
                    <circle cx="47.5" cy="32.5" r="2.5" transform="rotate(90 47.5 32.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="62.5" r="2.5" transform="rotate(90 17.5 62.5)" fill="#E0ECF7"></circle>
                    <circle cx="17.5" cy="82.5" r="2.5" transform="rotate(90 17.5 82.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="92.5" r="2.5" transform="rotate(90 2.5 92.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="112.5" r="2.5" transform="rotate(90 2.5 112.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="72.5" r="2.5" transform="rotate(90 2.5 72.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="82.5" r="2.5" transform="rotate(90 2.5 82.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="102.5" r="2.5" transform="rotate(90 2.5 102.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="12.5" r="2.5" transform="rotate(90 2.5 12.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="42.5" r="2.5" transform="rotate(90 2.5 42.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="22.5" r="2.5" transform="rotate(90 2.5 22.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="52.5" r="2.5" transform="rotate(90 2.5 52.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="32.5" r="2.5" transform="rotate(90 2.5 32.5)" fill="#E0ECF7"></circle>
                    <circle cx="2.5" cy="62.5" r="2.5" transform="rotate(90 2.5 62.5)" fill="#E0ECF7"></circle>
                </svg>
            </div>

            <div class="card">

                <div class="card__visa">
                    <svg class="visa" enable-background="new 0 0 291.764 291.764" version="1.1" viewBox="5 70 290 200"
                        xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <path class="svgcolor"
                            d="m119.26 100.23l-14.643 91.122h23.405l14.634-91.122h-23.396zm70.598 37.118c-8.179-4.039-13.193-6.765-13.193-10.896 0.1-3.756 4.24-7.604 13.485-7.604 7.604-0.191 13.193 1.596 17.433 3.374l2.124 0.948 3.182-19.065c-4.623-1.787-11.953-3.756-21.007-3.756-23.113 0-39.388 12.017-39.489 29.204-0.191 12.683 11.652 19.721 20.515 23.943 9.054 4.331 12.136 7.139 12.136 10.987-0.1 5.908-7.321 8.634-14.059 8.634-9.336 0-14.351-1.404-21.964-4.696l-3.082-1.404-3.273 19.813c5.498 2.444 15.609 4.595 26.104 4.705 24.563 0 40.546-11.835 40.747-30.152 0.08-10.048-6.165-17.744-19.659-24.035zm83.034-36.836h-18.108c-5.58 0-9.82 1.605-12.236 7.331l-34.766 83.509h24.563l6.765-18.08h27.481l3.51 18.153h21.664l-18.873-90.913zm-26.97 54.514c0.474 0.046 9.428-29.514 9.428-29.514l7.13 29.514h-16.558zm-160.86-54.796l-22.931 61.909-2.498-12.209c-4.24-14.087-17.533-29.395-32.368-36.999l20.998 78.33h24.764l36.799-91.021h-24.764v-0.01z"
                            fill="#FFFFFF"></path>
                        <path class="svgtipcolor"
                            d="m51.916 111.98c-1.787-6.948-7.486-11.634-15.226-11.734h-36.316l-0.374 1.686c28.329 6.984 52.107 28.474 59.821 48.688l-7.905-38.64z"
                            fill="#FFFFFF"></path>
                    </svg>
                </div>

                <div class="card__number">0000&nbsp;0000&nbsp;0000&nbsp;0000</div>
                <div class="card__name">
                    <h3>Proprietário do cartão</h3>
                    <p id="card-name">
                        
                    </p>
                </div>

                <div class="card__expiry">
                    <h3>Válido até</h3>
                    <p>
                        <span id="month">MM</span>/<span id="year">YY</span>
                    </p>
                </div>
            </div>

            <form class="form">
                <h2>Detalhes do Cartão</h2>

                <div class="form__name form__detail">
                    <label for="name">Nome</label>
                    <ion-icon name="person-outline" role="img" class="md hydrated"
                        aria-label="person outline"></ion-icon>
                    <input type="text" placeholder="Nome do cartão" id="name" maxlength="24">
                    <div class="alert" id="alert-1">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.3163 9.00362C11.8593 10.0175 11.1335 11.25 9.99343 11.25H2.00657C0.866539 11.25 0.140732 10.0175 0.683711 9.00362L4.67714 1.54691C5.24618 0.484362 6.75381 0.484362 7.32286 1.54691L11.3163 9.00362ZM5.06238 4.49805C5.02858 3.95721 5.4581 3.5 6 3.5C6.5419 3.5 6.97142 3.95721 6.93762 4.49805L6.79678 6.75146C6.77049 7.17221 6.42157 7.5 6 7.5C5.57843 7.5 5.22951 7.17221 5.20322 6.75146L5.06238 4.49805ZM6 8C5.44772 8 5 8.44772 5 9C5 9.55229 5.44772 10 6 10C6.55228 10 7 9.55229 7 9C7 8.44772 6.55228 8 6 8Z"
                                fill="#FF6A96"></path>
                        </svg>
                        Nome inválido
                    </div>
                </div>

                <div class="form__number form__detail">
                    <label for="number">Número do cartão</label>
                    <ion-icon name="card-outline" role="img" class="md hydrated" aria-label="card outline"></ion-icon>
                    <input type="text" placeholder="0000 0000 0000 0000" id="number"
                        onkeypress="return isNumeric(event)">
                    <div class="alert" id="alert-2">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.3163 9.00362C11.8593 10.0175 11.1335 11.25 9.99343 11.25H2.00657C0.866539 11.25 0.140732 10.0175 0.683711 9.00362L4.67714 1.54691C5.24618 0.484362 6.75381 0.484362 7.32286 1.54691L11.3163 9.00362ZM5.06238 4.49805C5.02858 3.95721 5.4581 3.5 6 3.5C6.5419 3.5 6.97142 3.95721 6.93762 4.49805L6.79678 6.75146C6.77049 7.17221 6.42157 7.5 6 7.5C5.57843 7.5 5.22951 7.17221 5.20322 6.75146L5.06238 4.49805ZM6 8C5.44772 8 5 8.44772 5 9C5 9.55229 5.44772 10 6 10C6.55228 10 7 9.55229 7 9C7 8.44772 6.55228 8 6 8Z"
                                fill="#FF6A96"></path>
                        </svg>
                        Número do cartão inválido
                    </div>
                </div>

                <div class="form__expiry form__detail">
                    <label for="date">Data de Vencimento</label>
                    <ion-icon name="calendar-outline" role="img" class="md hydrated"
                        aria-label="calendar outline"></ion-icon>
                    <input type="text" placeholder="M/A" id="date" onkeypress="return isNumeric(event)">
                    <div class="alert" id="alert-3">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.3163 9.00362C11.8593 10.0175 11.1335 11.25 9.99343 11.25H2.00657C0.866539 11.25 0.140732 10.0175 0.683711 9.00362L4.67714 1.54691C5.24618 0.484362 6.75381 0.484362 7.32286 1.54691L11.3163 9.00362ZM5.06238 4.49805C5.02858 3.95721 5.4581 3.5 6 3.5C6.5419 3.5 6.97142 3.95721 6.93762 4.49805L6.79678 6.75146C6.77049 7.17221 6.42157 7.5 6 7.5C5.57843 7.5 5.22951 7.17221 5.20322 6.75146L5.06238 4.49805ZM6 8C5.44772 8 5 8.44772 5 9C5 9.55229 5.44772 10 6 10C6.55228 10 7 9.55229 7 9C7 8.44772 6.55228 8 6 8Z"
                                fill="#FF6A96"></path>
                        </svg>
                        Data inválida
                    </div>
                </div>

                <div class="form__cvv form__detail">
                    <label for="cvv">CVV </label>
                    <ion-icon name="lock-closed-outline" role="img" class="md hydrated"
                        aria-label="lock closed outline"></ion-icon>
                    <input type="password" placeholder="000" id="cvv" maxlength="3"
                        onkeypress="return isNumeric(event)">
                    <div class="alert" id="alert-4">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.3163 9.00362C11.8593 10.0175 11.1335 11.25 9.99343 11.25H2.00657C0.866539 11.25 0.140732 10.0175 0.683711 9.00362L4.67714 1.54691C5.24618 0.484362 6.75381 0.484362 7.32286 1.54691L11.3163 9.00362ZM5.06238 4.49805C5.02858 3.95721 5.4581 3.5 6 3.5C6.5419 3.5 6.97142 3.95721 6.93762 4.49805L6.79678 6.75146C6.77049 7.17221 6.42157 7.5 6 7.5C5.57843 7.5 5.22951 7.17221 5.20322 6.75146L5.06238 4.49805ZM6 8C5.44772 8 5 8.44772 5 9C5 9.55229 5.44772 10 6 10C6.55228 10 7 9.55229 7 9C7 8.44772 6.55228 8 6 8Z"
                                fill="#FF6A96"></path>
                        </svg>
                        CVV invalido
                    </div>
                </div>

                <div class="buttons">
                    <form action="processar_pagamento.php" method="POST">
                        <input type="hidden" name="valor" value="<?php echo htmlspecialchars($valor); ?>">
                        <a href=""><button id="button-payment" type="submit" class="form__btn"><span></span>Confirmar</button></a>
                           
                        </form>
                </div>

            </form>

        </div>
    <div class="cart-container">
        <div class="cart-header">
            <h2>Meu Carrinho</h2>
            <p style="display: none;"><strong>Pacote ID:</strong> <?php echo htmlspecialchars($id); ?></p>
        </div>
        <div class="cart-item">
            <h3><?php echo htmlspecialchars($nome); ?> <span class="price">R$ <span id="preco-item"><?php echo htmlspecialchars($valor); ?></span></span></h3>
            <p> <?php echo htmlspecialchars($descricao); ?></p>
        </div>

        <!-- Onde o desconto será exibido -->
        <div id="desconto" class="cart-item promo-code" style="display: none;">
            <h3>Cupom <span class="price">−R$ <span id="valor-desconto"></span></span></h3>
            <p id="cupom-aplicado"></p>
        </div>
        <!-- Total -->
        <div class="container-descont">
            <div class="total">
                Total (R$) <span>R$ <span id="total-valor"><?php echo htmlspecialchars($valor); ?></span></span>
            </div>
            <!-- Entrada para cupom -->
            <div class="promo-input">
                <input type="text" id="input-cupom" placeholder="Cupom de Desconto">
                <button id="btn-verificar">Verificar</button>
            </div>
        </div>
    </div>   
 </div>
</body>
</html>
<?php
else:
    echo "Erro: Informações incompletas.";
endif;
?>

    <script src="pagamento.js"></script>
    <script>
    // Lista de cupons válidos e seus descontos
    const cupons = {
            "EXPLORACAR": 10.00,
            "DESCONTO15": 15.00
        };

        // Função para verificar e aplicar o cupom
        document.getElementById('btn-verificar').addEventListener('click', () => {
            const cupomInput = document.getElementById('input-cupom').value.toUpperCase(); // Converte o cupom para maiúsculas
            const descontoCard = document.getElementById('desconto'); // Card do desconto
            const precoItem = parseFloat(document.getElementById('preco-item').innerText); // Preço do item
            const totalValor = document.getElementById('total-valor'); // Total atualizado

            // Verifica se o cupom é válido
            if (cupons[cupomInput]) {
                const desconto = cupons[cupomInput]; // Obtém o valor do desconto

                // Atualiza o card de desconto
                descontoCard.style.display = 'block';
                document.getElementById('valor-desconto').innerText = desconto.toFixed(2);
                document.getElementById('cupom-aplicado').innerText = cupomInput;

                // Atualiza o valor total
                const novoTotal = Math.max(precoItem - desconto, 0);
                totalValor.innerText = novoTotal.toFixed(2);
            } else {
                alert('Cupom inválido!'); // Mensagem de erro se o cupom for inválido
            }
        });
    
</script>


</body>

</html>