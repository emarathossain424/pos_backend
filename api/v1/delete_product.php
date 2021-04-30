<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../config/Database.php';
include_once '../../config/helpers.php';
include_once '../../config/settings.php';
include_once '../../vendor/autoload.php';
include_once '../../models/Product.php';
include_once '../../models/User.php';
include_once '../../models/Category.php';
include_once '../../controllers/AuthController.php';
include_once '../../controllers/ProductController.php';

cors();
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo errorMessages(500,['error'=>"Please send a post request"]);
}
else{
    $data = json_decode(file_get_contents("php://input"));

    $headers = getallheaders();
    $jwt=$headers['Authorization'];

    $config=config();


    $auth=new AuthController($data,$config,$jwt);
    $response=$auth->isAuthenticated();
    if($response['is_validated'] && $response['is_login']){
        $product=new ProductController($data,$config);
        $response=$product->deleteProduct();
        echo $response;
    }
    else{
        echo errorMessages(403,['error'=>$response['error_msg']]);
    }
}