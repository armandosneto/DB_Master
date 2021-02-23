<?php

require_once('config.php');

$conn = new DB_Master();

$conn->config("mysql","dbphp8","root","","localhost", true);

//insert example
/*$conn->action("insert into tb_usuarios (deslogin, dessenha) values(:LOGIN, :PASSWORD)",array(
    ":LOGIN"=> "Teste",
    ":PASSWORD"=>"0598437530239"
));

//rollback in this action
$conn->rollback();

//update exemple
$conn->action("update tb_usuarios set dessenha = :PASSWORD where deslogin = :LOGIN", array(
    ":PASSWORD"=>"password",
    ":LOGIN"=> "Suely"
));*/

//commiting on DB
//$conn->commit();

//select example
echo $conn->action("select * from tb_usuarios order by idusuario");


