<?php
// Define a página destino
$paginaDestino = "../historico/historico.php";

// Envia cabeçalho de redirecionamento como fallback
header("Refresh: 6; url=$paginaDestino");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader</title>
    <meta http-equiv="refresh" content="6;url=<?php echo $paginaDestino; ?>">
    <link rel="stylesheet" href="loader.css">
</head>
<body>
    <main>
        <div class="loader">

        </div>
    </main>
</body>
</html>