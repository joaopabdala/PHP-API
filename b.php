<?php 
public function update_product()
{
    // check if all data is available
    if (
        !isset($this->params['id_produto']) ||
        !isset($this->params['produto']) ||
        !isset($this->params['quantidade'])
    ) {
        return $this->error_response('Insufficient product data.');
    }

    // check if there is already another product with the same
    $db = new database();
    $params = [
        ':id_produto' => $this->params['id_produto'],
        ':produto' => $this->params['produto'],
    ];
    $results = $db->EXE_QUERY("
        SELECT id_produto FROM produtos
        WHERE produto = :produto
        AND deleted_at IS NULL
        AND id_produto <> :id_produto
    ", $params);
    if (count($results) != 0) {
        return $this->error_response('There is already another product with the same name.');
    }

    // edit product in the database
    $params = [
        ':id_produto' => $this->params['id_produto'],
        ':produto' => $this->params['produto'],
        ':quantidade' => $this->params['quantidade']
    ];

    $db->EXE_NON_QUERY("
        UPDATE produtos SET
        produto = :produto,
        quantidade = :quantidade
        WHERE id_produto = :id_produto
    ", $params);

    return [
        'status' => 'SUCCESS',
        'message' => 'Product updated with success.',
        'results' => []
    ];
}

?>