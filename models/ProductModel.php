<?php 
class ProductModel {
    private $mysql_db;
    private $table = "products";
    public function __construct() {
        $this->mysql_db = new Database();
    }

    public function get($limit, $offset){
        if((int)$limit <= 0 || (int)$offset < 0) {
            return ['data' => [], 'total' => 0];
        }
        $result = $this->mysql_db->select($this->table, "*", null, null, null, $limit, $offset);
        return $result;
    }

    public function getOne($id){
        $sql = "SELECT * FROM ".$this->table." WHERE product_id=$id";
        $result = $this->mysql_db->query($sql)->fetch_assoc();
        return $result;
    }

    public function search($searchs = [], $limit, $offset){
        if(!empty($searchs)){
            if((int)$limit <= 0 || (int)$offset < 0) {
                return ['data' => [], 'total' => 0];
            }
            $result = $this->mysql_db->select($this->table, "*", null, $searchs, null, $limit, $offset);
        }
        return $result;
    }

    public function create($data = []){
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $result = $this->mysql_db->insert($this->table, array_values($data), array_keys($data));  
        return $result;
    }

    public function update($id, $data){
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        $result = $this->mysql_db->update($this->table, $data, 'product_id='.$id);  
        return $result;
    }

    public function delete($id){
        $result = $this->mysql_db->delete($this->table, 'product_id='.$id);  
        return $result;
    }
}

?>