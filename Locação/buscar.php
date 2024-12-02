<?php
// Captura o termo da pesquisa
$termo = isset($_GET['query']) ? trim(strtolower($_GET['query'])) : '';  // converte para minúsculas para maior flexibilidade

// Definir as palavras-chave para cada página
$redirecionamentos = [
    "duvidas" => ["duvidas", "fale conosco", "FAQ", "perguntas", "contato", "suporte", "ajuda"],
    "carros" => ["carros", "veículos", "aluguel de carros", "alugar carro", "aluguel", "carro", "kwid"],
    "assinatura" => ["assinatura", "assinaturas", "pacotes de assinaturas", "pacote de assinatura", "pacotes"]
];

// Verifica se o termo corresponde a alguma palavra-chave e redireciona
foreach ($redirecionamentos as $pagina => $palavras_chave) {
    if (in_array(strtolower($termo), $palavras_chave)) {
        switch ($pagina) {
            case "duvidas":
                header("Location: ../loc/duvidasfrequentes/duvidas.php");
                exit;
            case "carros":
                header("Location: ../Locação/veiculos.html");
                exit;
            case "assinatura":
                header("Location: ../assinatura/assinatura.php");
                exit;
        }
    }
}

// Caso o termo não corresponda, exibe uma mensagem ou direciona para uma página genérica
echo "<h1>Termo não encontrado</h1>";
echo "<a href='index.php'>Voltar</a>";
?>

