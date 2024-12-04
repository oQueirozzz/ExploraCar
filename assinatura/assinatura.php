<?php
session_start();
$pacotes = [
    [
        "id" => 1,
        "title" => "Plano Pro",
        "desc" => "Pacote com alguns benefícios para o seu aluguel",
        "price" => 29.99,
    ],
    [
        "id" => 2,
        "title" => "Plano Ultimate",
        "desc" => "Pacote com diverso benefícios para o seu aluguel",
        "price" => 49.99,
    ],
];



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: ../pagamento/pagamento.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global/global.css">
    <link rel="stylesheet" href="assinatura.css">
    <script src="assinatura.js"></script>
    <title>Pacotes de Assinatura - ExploraCar</title>
</head>

<body>
    <main class="main flow">
        <h1 class="main__heading">Pacotes de Assinatura</h1>
        <div class="main__cards cards">
            <div class="cards__inner" >
                <!-- Pacote Básico -->
                

                <!-- Pacotes Dinâmicos -->
                <?php foreach ($pacotes as $pacote): ?>
                    <div class="cards__card card">
                        <h2 class="card__heading"><?php echo htmlspecialchars($pacote['title']); ?></h2>
                        <p class="card__price">R$ <?php echo number_format($pacote['price'], 2, ',', '.'); ?></p>
                        <p>por mês</p>
                        <ul role="list" class="card__bullets flow">
                            <li>Acesso a lista de carros com variedades de modelos</li>
                            <li>Proteção contra danos e roubo, além de assistência 24h</li>
                            <li>Inclusão de acessórios como cadeiras de bebê ou assentos elevados sem custo</li>
                            <li>Processo acelerado para retirada e devolução do veículo</li>
                        </ul>
                        <div class="buttons">
                            <form action="../pagamento/pagamento.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $pacote['id']; ?>">
                                <input type="hidden" name="title" value="<?php echo htmlspecialchars($pacote['title']); ?>">
                                <input type="hidden" name="desc" value="<?php echo htmlspecialchars($pacote['desc']); ?>">
                                <input type="hidden" name="price" value="<?php echo htmlspecialchars(number_format($pacote['price'], 2, '.', '')); ?>">
                                <button id="button-assina" type="submit"><span></span>Assinar</button>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>

</html>