<?php

require_once("config.php");

//Load an user by ID 

//$user = new User();
//$user->loadById("Armando", "123456");
//echo $user;



//Load a user list

//$list = User::getList();
//echo json_encode($list);



//Load an user searching by login

//echo json_encode(User::search("teste"));



//Load a user by login and senha

//$user = new User();
//$user->login("Pedro", "567890");
//echo $user;



//Insert a user from the database

//$user = new User("Breno", "12131415");
//$user->insert();
//echo $user;



//Update a user from the database

//$user = new user();
//$user->loadById(10);
//$user->update("Armandinho", "test");


//Delete a user from the database
$user = new user();
$user->loadById(12);
$user->Delete();

echo $user->getDeslogin();
