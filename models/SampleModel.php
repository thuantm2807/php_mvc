<?php 
class SampleModel {
    
    public function sample_query(){        
        $mysql = new Database();
        $result = $mysql->query("select * from users");

        // Associative array
        $row = $result -> fetch_assoc();
        // Free result set
        $result -> free_result();

        return $row;
    }
}

?>