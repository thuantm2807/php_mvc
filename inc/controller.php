<?php 
class Controller {

    protected $render;

    public function __construct(){
        $this->render = new Render();
    }
    public function load_model($model){
        $model_main = new Model();
        return $model_main->load_model($model);
    }
    protected function verify_token(){
        $headers = apache_request_headers();
        if(!empty($headers)){
            $user_model = $this->load_model('UserModel');
            $token = $this->getBearerToken($headers);
            $result = $user_model->findToken($token);
            if(!empty($result)){
                return $result;
            } 
        }
        echo json_encode(['status_code' => 401,'message' => 'Token not found!']);
        die();
    }
    protected function getBearerToken($headers) {
        // HEADER: Get the access token from the header
        if (!empty($headers["Authorization"])) {
            if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}