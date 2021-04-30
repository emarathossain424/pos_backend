<?php
use Firebase\JWT\JWT;

class AuthController
{
    public $data;
    public $user;
    public $config;
    public $jwt;

    public function __construct($data,$config,$jwt=null)
    {
        $database = new database($config);
        $db = $database->connect();
        $this->user = new User($db);
        $this->data = $data;
        $this->config = $config;
        $this->jwt = $jwt;
    }

    /**
     * Will try to attempt login
     * @return false|string
     */
    public function login()
    {
        try {
            $validation = $this->isRequiredFieldEmpty();
            if (!$validation['is_validated']) {
                return validationErrorMessages(403, $validation['errors']);
            } else {
                $this->user->email = $this->data->email;
                $user_details = $this->user->getUserDetailsByEmailAddress();

                if ($user_details) {
                    $password=$user_details['password'];

                    if(password_verify($this->data->password,$password)){
                        $jwt_details=$this->prepareJWTResponse($user_details);
                        return successMessages($jwt_details,"Login successful");
                    }
                    else{
                        return validationErrorMessages(403,['password'=>"Incorrect password please try again"]);
                    }
                } else {
                    return validationErrorMessages(403,['email'=>"Invalid email address"]);
                }
            }
        }
        catch (Exception $e){
            $error=['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500,$error,"Unable to login please try later");
        }
    }

    /**
     * Will validate required fields while logging
     * @return array
     */
    public function isRequiredFieldEmpty()
    {
        $errors=[];
        if (empty($this->data->email)) {
            $errors['email']='Email field is required';
        }
        if (empty($this->data->password)) {
            $errors['password']='Password field is required';
        }
        if(sizeof($errors)>0){
            return [
                'is_validated' => false,
                'errors' => $errors
            ];
        }
        return [
            'is_validated' => true,
        ];
    }

    /**
     * Will prepare JWT response
     * @param $user_details
     * @return array
     */
    public function prepareJWTResponse($user_details){
        $secret_key=$this->config['jwt']['secret_key'];
        $payload_info=[
            'iss'=>$this->config['server_name'],
            'iat'=>time(),
            'nbf'=>time()+$this->config['jwt']['token_activate_after'],
            'exp'=>time()+$this->config['jwt']['token_duration_in_seconds'],
            'user'=>[
                'user_type'=>$user_details['user_type'],
                'id'=>$user_details['id'],
                'name'=>$user_details['name'],
                'email'=>$user_details['email']
            ]
        ];
        $token = JWT::encode($payload_info,$secret_key);
        $response=[
            'token'=>$token,
            'user'=> [
                'user_type'=>$user_details['user_type'],
                'id'=>$user_details['id'],
                'name'=>$user_details['name'],
                'email'=>$user_details['email']
            ]
        ];
        return $response;
    }

    /**
     * Will verify jwt token
     * @return array
     */
    public function isAuthenticated(){
        try {
            $secretKey  = $this->config['jwt']['secret_key'];
            $token = JWT::decode($this->jwt, $secretKey, array('HS256'));
            $now = time();
            $serverName = $this->config['server_name'];

            if ($token->iss !== $serverName || $token->nbf > $now || $token->exp < $now)
            {
                return [
                    'is_validated'=>true,
                    'is_login'=>false,
                    'msg'=>'Invalid Token'
                ];
            }
            else{
                return [
                    'is_validated'=>true,
                    'is_login'=>true,
                    'user'=>$token->user
                ];
            }
        }
        catch (Exception $e){
            return [
                'is_validated'=>false,
                'error_msg'=>"".$e->getMessage()
            ];
        }
    }
}