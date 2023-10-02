<?php 

require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$error_message = '';
$success_message = '';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    if(!isset($_GET['id'])){
        header('Location: produtos.php');
    } 
    $product = api_request('get_product','POST', ['id' => $_GET['id']]);
    $product = $product['data']['results'][0];

}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id_produto = $_POST['id_produto'];
    $produto = $_POST['text_produto'];
    $quantidade = $_POST['text_quantidade'];

    $results = api_request('update_product', 'POST', [
        'id_produto' => $id_produto,
        'produto' => $produto,
        'quantidade' => $quantidade
    ]);



    if($results['data']['status'] == 'ERROR'){
        $error_message = $results['data']['message'];
    } elseif($results['data']['status'] == 'SUCCESS'){
        $success_message = $results['data']['message'];

    }

    $product = api_request('get_product','POST', ['id' => $id_produto]);
    $product = $product['data']['results'][0];

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
        <div class="row my-5">
            <form action="produtos_edit.php" method="POST">
            <input type="hidden" name="id_produto" value="<?=$product['id_produto']?>">
                <div class="col-sm-6 card offset-sm-3 bg-light p-4">
                    <div class="mb-3">
                        <label for="">Nome do Produto:</label>
                        <input type="text" name="text_produto" class="form-control" value="<?=$product['produto']?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Quantidade:</label>
                        <input type="number" name="text_quantidade" class="form-control" value="<?=$product['quantidade']?>">
                    </div>
                    <div class="mb-3 text-center">
                        <a href="produtos.php" class="mx-1 btn btn-secondary btn-sm">Cancelar</a>
                        <input type="submit" value="Guardar" class="mx-1 btn btn-primary btn-sm">
                    </div>

                    <?php if(!empty($error_message)):?>
                        <div class="alert alert-danger">
                            <?= $error_message?>
                        </div>
                        <?php elseif(!empty($success_message)):?>
                            <div class="alert alert-success">
                                <?= $success_message?>
                            </div>
                    <?php endif?>
                </div>
            </form>
        </div>
    </section>
    
<script src="./assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>