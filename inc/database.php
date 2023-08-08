<?php
class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $connection;
    public function __construct() {
        $this->connect();
    }
    public function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->connection->connect_error) {
            return false;
        }
        mysqli_options($this->connection, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        return true;
    }

    public function disconnect() {
        if ($this->connection) {
            if ($this->connection->close()) {
                return true;
            }
            return false;
        }
    }

    public function query($sql) {
        $result = $this->connection->query($sql);
        if ($result) {
            return $result;
        }
        return false;
    }

    public function select($table, $rows = '*', $where = null, $like = null, $order = null, $limit = 5, $offset = 0) {
        $sql = "SELECT $rows FROM $table";
        if ($where != null) {
            $sql .= " WHERE $where";
        }
        if ($like != null) {
            $sql = is_null($where) ? $sql." WHERE" : $sql;
            if(gettype($like) === "array"){
                $last_key = array_key_last($like);
                foreach ($like as $key=>$value) {
                    $sep = " AND";
                    if($key == $last_key) $sep = "";
                    $sql .= " $key LIKE '%$value%'".$sep;
                }
            } 
        }
        if ($order != null) {
            $sql .= " ORDER BY $order";
        }
        if ($limit != null) {
            $sql .= " LIMIT $limit";
        }
        if ($offset != null) {
            $sql .= " OFFSET $offset";
        }

        $data = [];
        $result = $this->connection->query($sql);
        $total = $result->num_rows;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return compact('data', 'total');
    }

    public function insert($table, $values, $columns = null) {
        $sql = "INSERT INTO $table";
        if ($columns != null) {
            $columns = implode(", ", $columns);
            $sql .= " ($columns)";
        }
        $values = implode("', '", $values);
        $sql .= " VALUES ('$values')";
        return $this->query($sql);
    }

    public function update($table, $sets, $where = null) {
        $i = 0;$len = count($sets);
        $sql = "UPDATE $table SET ";
        foreach ($sets as $key => $value) {
            $i++;$sep=',';
            if($i === $len) {
                $sep = '';
            }
            $value = is_string($value) ? "'$value'" : $value;
            $sql .= $key."=".$value . $sep;
        }
        if ($where != null) {
            $sql .= " WHERE $where";
        }
        return $this->query($sql);
    }

    public function delete($table, $where = null) {
        $sql = "DELETE FROM $table";
        if ($where != null) {
            $sql .= " WHERE $where";
        }
        return $this->query($sql);
    }

    public function cast_query_results($rs) {
        $fields = mysqli_fetch_field($rs);
        $data = array();
        $types = array();
        foreach($fields as $field) {
            switch($field->type) {
                case 3:
                    $types[$field->name] = 'int';
                    break;
                case 4:
                    $types[$field->name] = 'float';
                    break;
                default:
                    $types[$field->name] = 'string';
                    break;
            }
        }
        while($row=mysqli_fetch_assoc($rs)) array_push($data,$row);
        for($i=0;$i<count($data);$i++) {
            foreach($types as $name => $type) {
                settype($data[$i][$name], $type);
            }
        }
        return $data;
    }
}