<?php

class User {

    private $idusario;
    private $deslogin;
    private $dessenha;
    private $dtcadastro;

    
    public function getIdusario(){
        return $this->idusario;
    }

    public function setIdusario($idusario){
        $this->idusario = $idusario;
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

        if(count($results) != 1){
            throw new Error("Error: there's no register with this ID!",400);
        }

        $row = $results[0];

        $this->setIdusario($row['idusuario']);
        $this->setDeslogin($row['deslogin']);
        $this->setDessenha($row['dessenha']);
        $this->setDtcadastro(new DateTime($row['dtcadastro']));


    }

    public function __toString(){
        return json_encode(array(
            "idusuario" => $this->getIdusario(),
            "deslogin" => $this->getDeslogin(),
            "dessenha"=> $this->getDessenha(),
            "dtcadastro"=>$this->getDtcadastro()->format('d/m/Y H:i:s')
        ));
    }


}