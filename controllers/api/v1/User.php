<?php
class User extends Controller{
    private $user_model;
    public function __construct() {
        $this->mysql_db = new Database();
        $this->user_model = $this->load_model('UserModel');
    }

    public function login(){
        $request = json_decode(file_get_contents('php://input'), true);
        $username = $request['username'];
        $password = $request['password'];
        try {
            if(isset($username, $password) && $username !== '' && $password !== '') {
                $data = $this->user_model->login($request);
                if($data){
                    echo json_encode(['status_code' => 200,'data' => $data]);
                } else {
                    echo json_encode(['status_code' => 401,'message' => 'Wrong username and password']);
                };
            } else {
                echo json_encode(['status_code' => 401,'message' => 'Wrong username and password']);
            }
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function signup(){
        $request = json_decode(file_get_contents('php://input'), true);

        $username = $request['username'];
        $password = $request['password'];
        $email = $request['email'];
        $full_name = $request['full_name'];

        try {
            if(isset($username, $password) 
                && $username !== ''
                && $password !== ''
                && $email !== ''
                && $full_name !== ''
                ) {
                $data = compact('username', 'password','email', 'full_name');
                $response = $this->user_model->create($data);
                echo json_encode(['status_code' => 200, 'message' => "User created successfully."]);
            } else {
                throw new Exception("Store name is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function logout(){
        $this->verify_token();
        $headers = apache_request_headers();
        try {
            $token = $this->getBearerToken($headers);
            $result = $this->user_model->findToken($token);
            if(!empty($result)){
                $result = $this->user_model->deleteToken($token);
            }
            echo json_encode(['status_code' => 200, 'message' => "User logged out successfully."]);
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'message' => $e->getMessage()]);
        }
        
    }
    
    public function update($id){
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            if(!empty($id) && !empty($data)) {
                $response = $this->user_model->update($id[0], $data);
                echo json_encode(['status_code' => 200, "message" => "User information updated successfully."]);
            } else {
                throw new Exception("Store name is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'updated' => false, 'message' => $e->getMessage()]);
        }
    }

    public function verify_user_token(){
        $result = $this->verify_token();
        if(!empty($result)){
            echo json_encode(['status_code' => 200,'message' => "Token is valid.", 'user_id'=>$result['user_id']]);
        };
    }
}