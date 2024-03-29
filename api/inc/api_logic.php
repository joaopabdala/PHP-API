<?php 

class api_logic{
    private $endpoint;
    private $params;


    public function __construct($endpoint, $params = null){
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    public function endpoint_exists(){
        return method_exists($this, $this->endpoint);
    }

    public function error_response($message){
        return[
            'status' => 'ERROR',
            'message' => $message,
            'results' => []
            ];
    }

    public function status(){
        return [
            'status' => 'SUCCESS',
            'message' => 'API is running OK!',
            'results' => null
        ];
    }

    public function get_totals(){
        $db = new database();
        $sql = "SELECT 'Clientes', COUNT(*) Total FROM clientes WHERE deleted_at IS NULL
        UNION ALL 
        SELECT 'Produtos', COUNT(*) Total FROM produtos WHERE deleted_at IS NULL";

        $results = $db->EXE_QUERY($sql);
        return [
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
        ];
    }

    // public function get_all_clients(){
    //     $sql = "SELECT * FROM clientes WHERE 1 ";

    //     if(key_exists('only_active', $this->params)){
    //         if(filter_var($this->params['only_active'], FILTER_VALIDATE_BOOLEAN) == true){
    //     //     if($this->params['only_active'] == 'true'){
    //             $sql .= "AND deleted_at IS NULL";
    //         }
    //     }

    //     $db = new database();
    //     $results = $db->EXE_QUERY($sql);
    //     return[
    //         'status' => 'SUCCESS',
    //         'message' => '',
    //         'results' => $results
    //         ];
    // }

    ///CLIENTES

    public function get_all_clients(){

        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM clientes");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function get_all_active_clients(){

        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM clientes WHERE deleted_at IS NULL");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function get_all_inactive_clients(){

        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM clientes WHERE deleted_at IS NOT NULL");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }

    public function get_client(){
        $sql = "SELECT * FROM clientes WHERE 1 ";

        if(key_exists('id', $this->params)){
            if(filter_var($this->params['id'],FILTER_VALIDATE_INT)){
                $sql .= "AND id_cliente = ". intval($this->params['id']);
            }
        } else {
            return $this->error_response("ID client not specified");
        }
        $db = new database();
        $results = $db->EXE_QUERY($sql);
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function create_new_client(){

        if(
            !isset($this->params['nome']) ||
            !isset($this->params['email']) ||
            !isset($this->params['telefone'])
        ){
            return $this->error_response('Insufficient client data.');
        }

        $db = new database();
        $params = [
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email'],
        ];

        $results = $db->EXE_QUERY("
            SELECT id_cliente FROM clientes
            WHERE 1
            AND (nome = :nome OR email = :email) 
            AND deleted_at IS NULL
        ", $params);
        if(count($results) != 0){
            return $this->error_response('There is already another client with the same name or email.');
        }


        $params = [
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email'],
            ':telefone' => $this->params['telefone'],
        ];
        

        $db->EXE_QUERY("INSERT INTO clientes VALUES(
            0,
            :nome,
            :email,
            :telefone,
            NOW(),
            NOW(),
            NULL
        )" , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'New client added with success',
            'results' =>[]
            ];
    }
    
    public function delete_client(){

        if(
            !isset($this->params['id']) 
        ){
            return $this->error_response('Insufficient client data.');
        }

        $db = new database();
        $params = [
            ':id_cliente' => $this->params['id'],
        ];
        

        $db->EXE_NON_QUERY("UPDATE clientes SET deleted_at = NOW() WHERE id_cliente = :id_cliente
            " , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'Client deleted with success',
            'results' =>[]
            ];


    }

    public function update_client(){
        if(
            !isset($this->params['id_cliente']) ||
            !isset($this->params['nome']) ||
            !isset($this->params['email']) ||
            !isset($this->params['telefone'])
        ){
            return $this->error_response('Insufficient client data.');
        }

        $db = new database();
        $params = [
            ':id_cliente' => $this->params['id_cliente'],
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email'],
        ];

        $results = $db->EXE_QUERY("
            SELECT id_cliente FROM clientes
            WHERE 1
            AND (nome = :nome OR email = :email) 
            AND deleted_at IS NULL
            AND id_cliente <> :id_cliente
        ", $params);
        if(count($results) != 0){
            return $this->error_response('There is already another client with the same name or email.');
        }


        $params = [
            ':id_cliente' => $this->params['id_cliente'],
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email'],
            ':telefone' => $this->params['telefone'],
        ];
        

        $db->EXE_NON_QUERY("UPDATE clientes SET
            nome = :nome,
            email = :email,
            telefone = :telefone,
            updated_at = NOW()
            WHERE id_cliente = :id_cliente
        " , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'Client updated with success',
            'results' =>[]
            ];
      
    }

    


    ///PRODUTOS



    public function get_all_products(){
        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM produtos");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];

    }

    public function get_product(){
        $sql = "SELECT * FROM produtos WHERE 1 ";

        if(key_exists('id', $this->params)){
            if(filter_var($this->params['id'],FILTER_VALIDATE_INT)){
                $sql .= "AND id_produto = ". intval($this->params['id']);
            }
        } else {
            return $this->error_response("ID product not specified");
        }
        $db = new database();
        $results = $db->EXE_QUERY($sql);
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function get_all_active_products(){
        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM produtos WHERE deleted_at IS NULL");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function get_all_inactive_products(){
        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM produtos WHERE deleted_at IS NOT NULL");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }
    public function get_all_products_without_stock(){
        $db = new database();
        $results = $db->EXE_QUERY("SELECT * FROM produtos WHERE quantidade <= 0 AND deleted_at IS NULL");
        return[
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $results
            ];
    }

    public function create_new_product(){
        
        if(
            !isset($this->params['produto']) ||
            !isset($this->params['quantidade'])
            
        ){
            return $this->error_response('Insufficient product data.');
        }

        $db = new database();
        $params = [
            ':produto' => $this->params['produto'],
        ];

        $results = $db->EXE_QUERY("
            SELECT id_produto FROM produtos
            WHERE produto = :produto
            AND deleted_at IS NULL
        ", $params);
        if(count($results) != 0){
            return $this->error_response('There is already another product with the same name');
        }


        $params = [
            ':produto' => $this->params['produto'],
            ':quantidade' => $this->params['quantidade'],
        ];
        

        $db->EXE_QUERY("INSERT INTO produtos VALUES(
            0,
            :produto,
            :quantidade,
            NOW(),
            NOW(),
            NULL
        )" , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'New product added with success',
            'results' =>[]
            ];
    }

    public function delete_product(){

        if(
            !isset($this->params['id']) 
        ){
            return $this->error_response('Insufficient product data.');
        }

        $db = new database();
        $params = [
            ':id_produto' => $this->params['id'],
        ];
        

        $db->EXE_NON_QUERY("UPDATE produtos SET deleted_at = NOW() WHERE id_produto = :id_produto
            " , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'product deleted with success',
            'results' =>[]
            ];


    }

    public function update_product(){
        if(
            !isset($this->params['id_produto']) ||
            !isset($this->params['produto']) ||
            !isset($this->params['quantidade'])
            
        ){
            return $this->error_response('Insufficient product data.');
        }

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
        if(count($results) != 0){
            return $this->error_response('There is already another product with the same name');
        }


        $params = [
            ':id_produto' => $this->params['id_produto'],
            ':produto' => $this->params['produto'],
            ':quantidade' => $this->params['quantidade']
        ];
        

        $db->EXE_NON_QUERY("UPDATE produtos SET
            produto = :produto,
            quantidade = :quantidade
            WHERE id_produto = :id_produto
        " , $params);
        return [
            'status' => 'SUCCESS',
            'message' => 'Product updated with success',
            'results' =>[]
            ];
    }
}

?>