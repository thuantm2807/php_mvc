<?php 
class UserModel {
    private $mysql_db;
    private $table = "users";
    public function __construct() {
        $this->mysql_db = new Database();
    }

    public function getOne($id){
        $sql = "SELECT * FROM ".$this->table." WHERE user_id=$id";
        $result = $this->mysql_db->query($sql)->fetch_assoc();
        return $result;
    }

    public function findToken($token){
        $sql = "SELECT * FROM sessions WHERE token='$token'";
        $result = $this->mysql_db->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return [];
    }

    public function update($id, $data){
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        $result = $this->mysql_db->update($this->table, $data, 'user_id='.$id);  
        return $result;
    }
    
    public function deleteToken($token){
        $sql = "DELETE FROM sessions WHERE token='$token'";
        $result = $this->mysql_db->query($sql);
        return $result;
    }

    public function createSession($user_id){
        $data=[
            "user_id" => $user_id,
            "ip_address" => "ip-fake",
            "user_agent" => "browser-fake",
            "token" => substr(md5(mt_rand()), 0, 40),
            "refresh_token" => substr(md5(mt_rand()), 0, 40),
            "token_expires_at" => date("Y-m-d H:i:s", strtotime("+30 day")),
            "refresh_token_expires_at" => date("Y-m-d H:i:s", strtotime("+100 day")),
        ];
        $result = $this->mysql_db->insert("sessions", array_values($data), array_keys($data));  
        return $result ? $data : false;
    }

    public function login($data = []){
        $sql = "SELECT * FROM ".$this->table." WHERE username='".$data['username']."' AND password='".$data['password']."'";
        $user_info = $this->mysql_db->query($sql);
        if ($user_info->num_rows > 0) {
            $user = $user_info->fetch_assoc();
            $sql_2 = "SELECT * FROM sessions WHERE user_id='".$user['user_id']."'";
            $session_info = $this->mysql_db->query($sql_2);
            if ($session_info->num_rows == 0) {
                $session = $this->createSession($user['user_id']);
            } else {
                $session = $session_info->fetch_assoc();
            }
            return array_merge($user, $session);
        }
        return false;
    }

    public function create($data = []){
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $result = $this->mysql_db->insert($this->table, array_values($data), array_keys($data));  
        return $result;
    }
}

?>