<?php
// Captura o termo da pesquisa
$termo = isset($_GET['query']) ? trim(strtolower(preg_replace('/\s+/', '', $_GET['query']))) : ''; // Remove espaços extras e converte para minúsculas


// Definir as palavras-chave para cada página
$redirecionamentos = [
    "duvidas" => ["duvidas", "faleconosco", "FAQ", "perguntas", "contato", "suporte", "ajuda"],
    "carros" => ["carros", "veículos","hilux", "camry", "prius", "rav4", "landcruiser", "ford", "broncosport", "ecosport", " fiesta", "fusion","kasedan", "focus", "mustang", "ranger", "edge", "explorer", "chevrolet", "onix", "trackerlt", "trackerpremier", "montanapremier", "onixpluspremier", "prisma", "spin", "equinox", "camaro", "s10", "unoway", "fiat", "palio", "argo", "toro", "siena", "cronos", "mobi", "500", "ducato", "panda", "honda", "hr-v", "fit", "city", "cr-v", "accord", "wr-v","pilot", "civictyper", "hr-vhybrid", "hb20", "creta", "tucson", "santafe", "elantra", "kona", "i30", "veloster", "azera", "nexo", "renault", "sandero", "duster", "captur", "logan", "clio", "talisman", "koleos", "fluence", "zoe", "kicks", "frontier", "leaf", "march", "pathfinder", "x-trail", "altima" , "skylinegt-r", "208", "pegeot", "3008", "2008", "408", "partner", "5008", "308", "508", "rcz", "106", "citroen", "c3", "c4cactus", "c5aircross", "berlingo", "jumper", "c3aircross", "jumpy", "ds3", "ds7crossback", "grandc4picasso", "volvoc40recharge", "lexuslc", "mercedes-benzamggt", "bmwx6", "volvov90", "lexuslx", "mercedes-benzs-class",  "alugueldecarros", "alugarcarro", "aluguel", "carro", "kwid","corolla","toyota","corolla cross", "pegeot", "pegeot208", "blackfriday", "audi", "audirs7", "audia8", "bmwi8", "versa", "sentra", "nissan","hyundai", "honda", "civic","yarishatch", "yarissedã", "sw4" ],
   "sobre" => [
    "sobre", "sobrenos", "quemsomos", "nossahistória", "história", 
    "sobrenós", "informaçõessobreaempresa", "empresa", "nós", 
    "missão", "visão", "valores", "sobreaempresa", "fundadores", 
    "equipe", "histórico", "sobremim", "sobreagente", "quemsomosnós"
    ],
    "assinatura" => ["assinatura", "assinaturas", "pacotesdeassinatura", "pacotesdeassinaturas", "pacotedeassinatura", "pacotes"],
    "blog" => ["blog", "artigos", "posts", "novidades", "notícias", "artigo", "conteúdo", "dicas", "informações", "tutoriais", "guia", "curiosidades", "atualizações", "novidade", "histórias", "opiniões"]
];


// Função para normalizar o texto removendo espaços e convertendo para minúsculas
function normalizar($texto) {
    return strtolower(preg_replace('/\s+/', '', $texto));
}
// Verifica se o termo corresponde a alguma palavra-chave e redireciona
foreach ($redirecionamentos as $pagina => $palavras_chave) {
    if (in_array(strtolower($termo), $palavras_chave)) {
        switch ($pagina) {
            case "duvidas":
                header("Location: ../loc/duvidasfrequentes/duvidas.php");
                exit;
            case "carros":
                header("Location: ../locação/veiculos.php");
                exit;
            case "assinatura":
                header("Location: ../assinatura/assinatura.php");
                exit;
                case "blog":
                    header("Location: blog.php");
                    exit;
                    case "sobrenos":
                        header("Location: ../loc/sobrenos/sobre.php");
                        exit;
        }
    }
}

// Caso o termo não corresponda, exibe uma mensagem ou direciona para uma página genérica
echo "<h1>Termo não encontrado</h1>";
echo "<a href='index.php'>Voltar</a>";
?>

