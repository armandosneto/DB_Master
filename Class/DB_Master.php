<?php

class DB_Master{

    private $type;
    private $dbname;
    private $host;
    private $username;
    private $password;
    private $conn;

    public function __construct($type, $dbname, $username, $password, $host = "localhost", $transaction = false){

        $this->type = $type;
        $this->dbname = $dbname;
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->conn = $this->connection();

        if ($transaction) {
            $this->conn->beginTransaction();
        }
    }

    private function connection(){
        //acessing PDO according to the correct DB type
        switch ($this->type) {

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

    public function action($query, array $values = array()):array{

        $stmt = $this->conn->prepare($query);
        $queryL = strtoupper($query);
        

        foreach ($values as $key => $value) {

            $this->setParam($stmt,$key, $value);

        }

        $stmt->execute();
        
        //select resquests treatment
        if (str_starts_with($queryL, 'SELECT') || str_starts_with($queryL, 'CALL')) {

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }

    }

    private function setParam($statement, $key, $value){
        $statement->bindParam($key, $value);
    }

    public function rollback(){
        $this->conn->rollback();
    }
    public function commit(){
        $this->conn->commit();
    }
}