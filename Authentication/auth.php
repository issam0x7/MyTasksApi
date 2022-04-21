<?php

require '../config.php';
require_once SYSTEM . 'startup.php';
require_once CONTROLLERS . 'UserController.php';
require_once 'generateJwt.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here



$validCredentials = true;
$controller = new UserController();
$data = $controller->auth();
if(empty($data)){
    $validCredentials = false;
}

if($validCredentials){
    $payload = array(
        "iat" => $issued,
        "exp" => $expire,
        "iss" => $issuer,
        "data" => $data,
    );


    $jwt = generateJWT($payload, SECRET);
    $responseMessage = ["message" => "Login success", "jwt" => $jwt];
    $controller->response->sendStatus(200);
    $controller->response->setContent($responseMessage);

    // echo json_encode(array("message" => "Login success", "jwt" => $jwt));
}
else{
    $controller->response->sendStatus(204);
    $controller->response->setContent(array("message" => "Login failed."));
    // http_response_code(401);
    // echo json_encode(array("message" => "Login failed."));
}

$controller->response->render();