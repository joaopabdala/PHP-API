<?php

require_once('inc/config.php');
require_once('inc/api_functions.php');

$results = api_request('get_all_products', 'GET');
if ($results['data']['status'] == 'SUCCESS') {
    $produtos = $results['data']['results'];
} else {
    $produtos = [];
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
            <div class="col">

                <div class="row">
                    <div class="col">
                        <h1>Produtos</h1>
                    </div>
                
                <div class="col text-end  align-self-center">
                    <a href="produtos_novo.php" class="btn btn-primary btn-sm">Adicionar produto...</a>
                </div>
            </div>

            <?php if (count($produtos) == 0) : ?>
                <p class="text-center">Não há produtos registrados</p>
            <?php else : ?>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto</th>
                            <th class="w-50 text-end">Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($produtos as $produto) : ?>
                            <tr>
                                <td class="w-50"><?= $produto['produto'] ?></td>
                                <td class="text-end"><?= $produto['quantidade'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <p>Total: <strong><?= count($produtos) ?></strong></p>

            <?php endif ?>
        </div>
        </div>

    </section>

    <script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>