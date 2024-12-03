<?php
// Captura o termo da pesquisa
$termo = isset($_GET['query']) ? trim(strtolower($_GET['query'])) : '';  // converte para minúsculas para maior flexibilidade

// Definir as palavras-chave para cada página
$redirecionamentos = [
    "duvidas" => ["duvidas", "fale conosco", "FAQ", "perguntas", "contato", "suporte", "ajuda", "pergunta", "duvida", "solicitar", "solicitar ajuda", "solicitar suporte", "solicitar contato", "solicitar fale conosco", "solicitar ajuda", "solicitar suporte", "solicitar contato", "solicitar fale conosco"],
    "carros" => ["carros", "veículos", "aluguel de carros", "alugar carro", "aluguel", "carro", "kwid", "corolla", "sedã", "cross", "sw4", "hilux", "cruiser", "mustang", "onix", "spin", "camaro", "s10", "gol", "jetta", "saveiro", "passat", "nivus", "uno", "palio", "toro", "mobi", "civic", "fit", "city", "type r", "hb20", "creta", "tucson", "i30", "azera", "sandero", "logan", "gtr", "peugeot", "toyota", "ford", "chevrolet", "nissan", "fiat", "honda", "volkswagen", "hyundai", "renault", "citroen"],
    "assinatura" => ["assinatura", "assinaturas", "pacotes de assinaturas", "pacote de assinatura", "pacotes", "Assinar", "assinar", "Plano", "plano", "planos", "Planos", "Assinatura", "assinatura", "Assinaturas", "assinaturas", "Pacotes de assinaturas", "Pacote de assinatura", "Pacotes", "pacotes"],
    "blog" => ["blog", "notícias", "noticia", "blogue", "noticias", "weblog", "diario", "diário", "Diário", "Blog", "BLOG", "BLOGUE", "Blog"] 
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
            case "blog":
                header("Location: ../blog/blog.php");
                exit;
        }
    }
}

// Caso o termo não corresponda, exibe uma mensagem ou direciona para uma página genérica
echo "<h1>Termo não encontrado</h1>";
echo "<a href='index.php'>Voltar</a>";
?>

