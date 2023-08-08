<?php 

class Routes {

    public function __construct($parameters){
        $params = explode("/", trim($parameters, "/"),5);
        // print_r($params);die;

        $controller = isset($params[2]) && !empty($params[2]) ? $params[2] : 'DefaultController';
        $instance =  isset($params[3]) && !empty($params[3]) ? $params[3] : 'index';
        $args = isset($params[4]) ? explode("/", trim($params[4], "/")) : []; 
       

        $controller = ucwords($controller);
        require_once "controllers/api/v1/{$controller}.php";
        if(class_exists($controller)){
            $controller_class = new $controller();
            if(method_exists($controller_class, $instance)){
                $controller_class->$instance($args);
            }else{
                die("Undefined {$instance} Method in {$controller} Controller");
            }
        }else{
            die("Undefined {$controller} Controller");
        }
    }
}