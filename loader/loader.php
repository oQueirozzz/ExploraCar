<?php
session_start(); // Inicia a sessão no início do arquivo

// Define a mensagem para exibir após o redirecionamento
$_SESSION['message'] = 'Operação realizada com sucesso!';

// Define a página destino
$paginaDestino = "../home/index.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loader</title>
    <!-- Redireciona para a página destino após 6 segundos -->
    <meta http-equiv="refresh" content="2;url=<?php echo $paginaDestino; ?>">
    <link rel="stylesheet" href="loader.css">
</head>
<body>
    <main>
        <div class="loader">
            <!-- Aqui você pode adicionar um texto, como: "Aguarde..." -->
        </div>
    </main>
</body>
</html>
