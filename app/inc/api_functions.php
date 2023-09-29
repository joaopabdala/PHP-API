<?php 

function api_request($endpoint, $method , $variables = []){

    //initiate client
    $client = curl_init();

    //return result as a string
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    //define url

    $url = API_BASE_URL;

    if($method == 'GET'){
        $url .= "?endpoint=$endpoint";
        if(!empty($variables)){
            $url .= "&" .  http_build_query($variables);
        }
    }

    if($method == 'POST'){
        $variables = array_merge(['endpoint'=> $endpoint], $variables);
        curl_setopt($client, CURLOPT_POSTFIELDS, $variables);
    }

    curl_setopt($client, CURLOPT_URL, $url);

    $response = curl_exec($client);
    return json_decode($response, true);

    

}

?>