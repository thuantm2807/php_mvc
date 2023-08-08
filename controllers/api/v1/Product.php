<?php
class Product extends Controller{
    private $product_model;
    public function __construct() {
        $this->mysql_db = new Database();
        $this->product_model = $this->load_model('ProductModel');
        $this->verify_token();
    }

    public function get(){
        $limit = isset($_GET['take']) ? $_GET['take'] : 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        try {
            $response = $this->product_model->get($limit, $offset);
            echo json_encode(['status_code' => 200, 'data' => $response["data"], 'total' => $response['total']]);
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function search(){
        $searchs = $_GET;
        array_splice($searchs,0,1);
        if(array_key_exists("take",$searchs)){
            $limit = (int)$searchs["take"];
            unset($searchs["take"]);
        } else {
            $limit = 5;
        }
        if(array_key_exists("page",$searchs)){
            $offset = ((int)$searchs["page"] - 1) * $limit;
            unset($searchs["page"]);
        } else {
            $offset = 0;
        }

        try {
            $response = [];
            if(!empty($searchs)) {
                $response = $this->product_model->search($searchs, $limit, $offset);
            }
            echo json_encode(['status_code' => 200, 'data' => $response["data"], 'total' => $response['total']]);
        } catch (Exception $e) {
            echo json_encode(['status_code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function detail($id){
        try {
            if(!empty($id)) {
                $response = $this->product_model->getOne($id[0]);
                echo json_encode(['status_code' => 200, 'data' => $response]);
            } else {
                throw new Exception("Product id is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
        }
    }
    public function create(){
        $request = json_decode(file_get_contents('php://input'), true);

        $product_name = $request["product_name"]; 
        $store_id = $request["store_id"]; 
        $price = $request["price"]; 
        $description = $request["description"]; 

        try {
            if(isset($product_name, $store_id, $price, $description) 
                && $product_name !== ''
                && $store_id !== ''
                && $price !== ''
                && $description !== '') {
                $data = compact('product_name','store_id','price','description');
                $response = $this->product_model->create($data);
                echo json_encode(['status_code' => 200, 'message' => "Product information created successfully.", 'data' => $data]);
            } else {
                throw new Exception("Product name is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['code' => 500, 'created' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update($id){
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            if(!empty($id) && !empty($data)) {
                $response = $this->product_model->update($id[0], $data);
                echo json_encode(['status_code' => 200, 'message' => "Product information updated successfully."]);
            } else {
                throw new Exception("Product name is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['code' => 500, 'updated' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id){
        try {
            if(!empty($id)) {
                $response = $this->product_model->delete($id[0]);
                echo json_encode(['status_code' => 200, 'message' => "Product information deleted successfully."]);
            } else {
                throw new Exception("Product id is empty", 1);
            }
        } catch (Exception $e) {
            echo json_encode(['code' => 500, 'deleted' => false, 'message' => $e->getMessage()]);
        }
    }
}