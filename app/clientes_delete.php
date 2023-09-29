<?php

require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$cliente_id = $_GET['id'];

if(!isset($cliente_id)){
    header('Location: clientes.php');
    exit;
}

$results = api_request('get_client', 'GET', ['id' => $cliente_id]);

// echo count($results);

if(count($results['data']['results']) == 0){
    header('Location: clientes.php');
    exit;
}

if ($results['data']['status'] == 'SUCCESS') {
    $cliente = $results['data']['results'][0];
} else {
    $cliente = [];
}

if(empty($cliente)){
    header('Location: clientes.php');
    exit;
}


if(isset($_GET['confirm']) && $_GET['confirm'] == 'true'){
    api_request('delete_client', 'GET', ['id' => $cliente_id]);
    header('Location: clientes.php');
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
                <h5 class="text-center">Deseja eliminar o cliente <strong><?=$cliente['nome']?></strong></h5>
                <div class="text-center mt-3">
                    <a class="btn  btn-primary" href="clientes.php">Cancelar</a>
                    <a class="btn  btn-danger" href="clientes_delete.php?id=<?= $cliente_id ?>&confirm=true">Deletar</a>
                </div>
            </div>
        </div>

    </section>

    <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>