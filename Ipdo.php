<?php

class Ipdo 
{
    private $id = "";
    public $login = "";
    public $password = "";
    public $email = "";
    public $firstname = "";
    public $lastname = "";

    constructeur($host, $username, $password, $db);

    connect($host, $username, $password, $db);
  
    destructeur();
  
    close();
  
    execute($query);
  
    getLastQuery();
   
    getLastResult();

    getTables();
  
    getFields($table);

}    

?>