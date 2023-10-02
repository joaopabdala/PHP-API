<?php 

require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$resultados = api_request('get_totals', 'GET')['data']['results'];


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Consumidora</title>
    <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php 
        include('inc/nav.php')
    ?>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-sm-6 text-center">
                Clientes:
                <?= $resultados[0]['Total'] ?>
            </div>
            <div class="col-sm-6 text-center">
                Produtos:
                <?= $resultados[1]['Total'] ?>
            </div>
        </div>
    </div>
<script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>