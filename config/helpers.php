<?php


/**
 * Will prepare and return error messages
 * @param $response_code
 * @param $errors
 * @return false|string
 */
function errorMessages($response_code,$errors){
    http_response_code($response_code);
    return json_encode([
      'success'=>false,
      'errors'=>$errors
    ]);
}


/**
 * Will prepare and return error messages on exception
 * @param $response_code
 * @param  array  $errors
 * @param  string  $message
 * @return false|string
 */
function errorMessagesForExceptions($response_code,$errors=[],$message=""){
    http_response_code($response_code);
    return json_encode([
        'success'=>false,
        'errors'=>$errors,
        'message'=>$message,
    ]);
}

/**
 * Will prepare and return validation error messages
 * @param $response_code
 * @param $errors
 * @return false|string
 */
function validationErrorMessages($response_code,$errors){
    http_response_code($response_code);
    return json_encode([
        'success'=>false,
        'errors'=>$errors
    ]);
}

/**
 * Will prepare and return success messages with data
 * @param $message
 * @param $details
 * @return false|string
 */
function successMessages($details,$message=""){
    http_response_code(200);
    return json_encode([
        'success'=>true,
        'message'=>$message,
        'details'=>$details,
    ]);
}

/**
 * Will prepare and return success messages with out data
 * @param $message
 * @return false|string
 */
function successMessagesWithoutData($message=""){
    http_response_code(200);
    return json_encode([
        'success'=>true,
        'message'=>$message,
    ]);
}

/**
 * Will handle CORS request
 */
function cors() {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");

        exit(0);
    }
}