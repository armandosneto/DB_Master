<?php

class DB_Master {

    private $type;
    private $dbname;
    private $host;
    private $username;
    private $password;
    private $conn;

    public function config($type, $dbname, $username, $password, $host = "localhost", $transaction=false){

        $this->type = $type;
        $this->dbname = $dbname;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;

        $this->conn = $this->connection();

        if($transaction){
            $this->conn->beginTransaction();
        }

    }

    private function connection(){
        //acessing PDO according to the correct DB type
        switch($this->type){

            case "pgsql":
                $dsn = $this->type . ":host=" . $this->host . ";dbname=" . $this->dbname . ";user=" . $this->username . ";password=" . $this->password;
                 return new PDO($dsn);
                break;
            case "mysql":
                $dsn = $this->type . ":host=" . $this->host . ";dbname=" . $this->dbname; 
                 return new PDO($dsn, $this->username, $this->password);
                break;
            default:
                throw new Exception("DB Master doesn't works with this Database");
                break;

        }
    
    }
    
    public function action($query , array $values = array()){

        $stmt = $this->conn->prepare($query);
        $queryL = strtolower($query);
       
        //select resquests treatment
        if(str_starts_with($queryL, 'select')){
    
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($results);
    
        }
        //select, update and detete requests treatment
        elseif(str_starts_with($queryL, 'insert into') || str_starts_with($queryL, 'update') || str_starts_with($queryL, 'delete')){
    
            $keys = array_keys($values);

            foreach($keys as $key){
                $stmt->bindParam($key, $values[$key]);
            }

            $stmt->execute();
    
        }

    }


    public function rollback(){
        $this->conn->rollback();
    }
    public function commit(){
        $this->conn->commit();
    }


}