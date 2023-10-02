<?php 

require_once('inc/config.php');
require_once('inc/api_functions.php');
require_once('inc/functions.php');

$error_message = '';
$success_message = '';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    if(!isset($_GET['id'])){
        header('Location: clientes.php');
    } 
    $client = api_request('get_client','POST', ['id' => $_GET['id']]);
    $client = $client['data']['results'][0];

}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id_cliente = $_POST['id_cliente'];
    $nome = $_POST['text_nome'];
    $email = $_POST['text_email'];
    $telefone = $_POST['text_telefone'];

    $results = api_request('update_client', 'POST', [
        'id_cliente' => $id_cliente,
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone
    ]);



    if($results['data']['status'] == 'ERROR'){
        $error_message = $results['data']['message'];
    } elseif($results['data']['status'] == 'SUCCESS'){
        $success_message = $results['data']['message'];

    }

    $client = api_request('get_client','POST', ['id' => $id_cliente]);
    $client = $client['data']['results'][0];

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
            <form action="clientes_edit.php" method="POST">
                <input type="hidden" name="id_cliente" value="<?=$client['id_cliente']?>">
                <div class="col-sm-6 card offset-sm-3 bg-light p-4">
                    <div class="mb-3">
                        <label for="">Nome do cliente:</label>
                        <input type="text" name="text_nome" class="form-control" value="<?=$client['nome']?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Email:</label>
                        <input type="text" name="text_email" class="form-control" value="<?=$client['email']?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Telefone:</label>
                        <input type="text" name="text_telefone" class="form-control" value="<?=$client['telefone']?>">
                    </div>
                    <div class="mb-3 text-center">
                        <a href="clientes.php" class="mx-1 btn btn-secondary btn-sm">Cancelar</a>
                        <input type="submit" value="Atualizar" class="mx-1 btn btn-primary btn-sm">
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