<?php

require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$produto_id = $_GET['id'];

if(!isset($produto_id)){
    header('Location: produtos.php');
    exit;
}

$results = api_request('get_product', 'GET', ['id' => $produto_id]);

// echo count($results);

if(count($results['data']['results']) == 0){
    header('Location: produtos.php');
    exit;
}

if ($results['data']['status'] == 'SUCCESS') {
    $produto = $results['data']['results'][0];
} else {
    $produto = [];
}

if(empty($produto)){
    header('Location: produtos.php');
    exit;
}


if(isset($_GET['confirm']) && $_GET['confirm'] == 'true'){
    api_request('delete_product', 'GET', ['id' => $produto_id]);
    header('Location: produtos.php');
    exit;
}


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
    <section class="container">
        <div class="row">
            <div class="col p-5">
                <h5 class="text-center">Deseja eliminar o produto <strong><?=$produto['produto']?></strong></h5>
                <div class="text-center mt-3">
                    <a class="btn  btn-primary" href="produtos.php">Cancelar</a>
                    <a class="btn  btn-danger" href="produtos_delete.php?id=<?= $produto_id ?>&confirm=true">Deletar</a>
                </div>
            </div>
        </div>

    </section>

    <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>