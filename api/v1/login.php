<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../config/Database.php';
include_once '../../config/helpers.php';
include_once '../../config/settings.php';
include_once '../../vendor/autoload.php';
include_once '../../models/User.php';
include_once '../../controllers/AuthController.php';

cors();
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo errorMessages(500,['error'=>"Please send a post request"]);
}
else{
    $data = json_decode(file_get_contents("php://input"));
    $config=config();

    $auth=new AuthController($data,$config);
    $response=$auth->login();
    echo $response;
}