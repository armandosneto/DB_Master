<?php

class User {

    private $idusuario;
    private $deslogin;
    private $dessenha;
    private $dtcadastro;
    

    public function __construct($login = "", $password = ""){
        $this->login = $login;
        $this->password = $password;

    }
    
    public function getIdusuario(){
        return $this->idusuario;
    }

    public function setIdusuario($idusuario){
        $this->idusuario = $idusuario;
    }

    public function getDeslogin(){
        return $this->deslogin;
    }

    public function setDeslogin($deslogin){
        $this->deslogin = $deslogin;
    }

    public function getDessenha(){
        return $this->dessenha;
    }

    public function setDessenha($dessenha){
        $this->dessenha = $dessenha;

    }

    public function getDtcadastro(){
        return $this->dtcadastro;
    }

    public function setDtcadastro($dtcadastro){
        $this->dtcadastro = $dtcadastro;
    }

    public function loadById($id){

        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);

        $results = $sql->action('SELECT * FROM tb_usuarios WHERE idusuario = :ID',array(
            ":ID"=> $id
        ));

        if(count($results,COUNT_NORMAL) != 1){
            throw new Error("Error: there's no register with this ID!",400);
        }
        
        $this->load($results[0]);
    }

    public static function getList(){
        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);
        return $sql->action("SELECT * FROM tb_usuarios ORDER BY deslogin;");
    }

    public static function search($login){

        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);
        return $sql->action("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin;", array(
            ":SEARCH" => "%".$login."%"
        ));

    }

    public function login($login, $password){

        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);
        
        $results = $sql->action("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND dessenha = :PASSWORD;", array(
            ":LOGIN" => $login,
            ":PASSWORD" => $password
        ));

        if(count($results) != 1){
            throw new Error("Error: there's no register with this Login or password!",400);
        }
        
        $this->load($results[0]);
    }

    public function insert(){
        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);
        $results = $sql->action("CALL sp_usuarios_insert(:LOGIN,:PASSWORD)",array(
            ":LOGIN" => $this->getDeslogin(),
            ":PASSWORD" => $this->getDessenha()
        ));

        if(count($results) != 1 || !is_null($results[0]["ERROR"])){
            throw new Error("Error: we couldn't insert this user!'",400);
        }
        
        $sql->commit();
        $this->load($results[0]);

    }

    public function update($login, $password){

        $this->setDeslogin($login);
        $this->setDessenha($password);

        $sql = new DB_Master("mysql","dbphp8","root","","localhost", true);

        $sql->action("UPDATE tb_usuarios SET deslogin = :LOGIN, dessenha = :PASSWORD WHERE idusuario = :ID", array(
            ":LOGIN"=> $this->getDeslogin(),
            ":PASSWORD" => $this->getDessenha(),
            ":ID" => $this->getIdusuario()
        ));

        $sql->commit();
    }

    public function __toString(){
        return json_encode(array(
            "idusuario" => $this->getIdusuario(),
            "deslogin" => $this->getDeslogin(),
            "dessenha"=> $this->getDessenha(),
            "dtcadastro"=>$this->getDtcadastro()->format('d/m/Y H:i:s')
        ));
    }

    private function load($row){

        $this->setIdusuario($row['idusuario']);
        $this->setDeslogin($row['deslogin']);
        $this->setDessenha($row['dessenha']);
        $this->setDtcadastro(new DateTime($row['dtcadastro']));

    }


}