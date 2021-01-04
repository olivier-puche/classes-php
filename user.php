<?php

class User 
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;
    
    public function register($login, $password, $email, $firstname, $lastname) 
    {
        $db = mysqli_connect('localhost', 'root', '', 'classes');
        if (!$db) 
        {
            return "échec de connexion".mysqli_connect_error();
        }
        $newuser = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$login','$password','$email','$firstname','$lastname')";
        mysqli_query($db, $newuser);
        return array($login, $password, $email, $firstname, $lastname);
    }
     
    public function connect ($login, $password) 
    {
        $db = mysqli_connect('localhost', 'root', '', 'classes');
        if (!$db) 
        {
            return "échec de connexion".mysqli_connect_error();
        }
        $connectuser = "SELECT * FROM utilisateurs WHERE login='$login'";
        $connect = mysqli_query($db, $connectuser);
        if ($select->num_rows==0) 
        {
            return "vous n'êtes pas inscrit";
        } 
        else 
        {
            $result = mysqli_fetch_array($select);
            if (password_verify($password,$result[2])) 
            {
                $this->id=$result[0];
                $this->login=$result[1];
                $this->password=$result[2];
                $this->email=$result[3];
                $this->firstname=$result[4];
                $this->lastname=$result[5];
                $userquery = [$this->id, $this->login, $this->password, $this->email, $this->firstname, $this->lastname];
                return $userquery;
            } 
            else 
            {
                return "mot de passe non valide";
            }
        }
    }

    public function disconnect() 
    {
        $this->id = '';
        $this->login = '';
        $this->password = '';
        $this->email = '';
        $this->firstname = '';
        $this->lastname = '';
    }

    public function delete() 
    {
        $db= mysqli_connect('localhost', 'root', '', 'classes');
        if (mysqli_connect_error()) 
        {
            return "échec de connexion".mysqli_connect_error();
            exit();
        }
        $deleteuser="DELETE FROM utilisateurs WHERE login='$this->login'";
        mysqli_query($db, $deleteuser);
        $this->disconnect();
    }

    public function update ($login, $password, $email, $firstname, $lastname) 
    {
        $this->login=$login;
        $this->password=$password;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;
        
        $db = mysqli_connect('localhost', 'root', '', 'classes');
        if (!$db) 
        {
            return "échec de connexion".mysqli_connect_error();
        }
        
        $updateuser = "UPDATE utilisateurs SET login='$this->login', password='$this->password', email='$this->email', firstname='$this->firstname', lastname='$this->lastname' WHERE id='$this->id'";
        mysqli_query($db, $updateuser);
        return array($login, $password, $email, $firstname, $lastname);
    }
    
    public function isConnected() 
    {
        if (isset($this->login)) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function getAllInfos() 
    {
        $info = [$this->id, $this->login, $this->password, $this->email, $this->firstname, $this->lastname];
        return $info;
    }

    public function getLogin() 
    {
        $login = $this->login;
        return $login;
    }

    public function getEmail() 
    {
        $email = $this->email;
        return $email;
    }

    public function getFirstname() 
    {
        $firstname = $this->firstname;
        return $firstname;
    }

    public function getLastname() 
    {
        $lastname = $this->lastname;
        return $lastname;
    }

    public function refresh() 
    {
        $db = mysqli_connect('localhost', 'root', '', 'classes');

        if (!$db) 
        {
            return "échec de connexion".mysqli_connect_error();
        }

        $connectuser = "SELECT * FROM utilisateurs WHERE login='$this->login'";
        $query = mysqli_query($db, $connectuser);     
        $result= mysqli_fetch_array($query);
        $this->id=$result[0];
        $this->login=$result[1];
        $this->password=$result[2];
        $this->email=$result[3];
        $this->firstname=$result[4];
        $this->lastname=$result[5];
        $array = [$this->id, $this->login, $this->password, $this->email, $this->firstname, $this->lastname];
        return $array;
    }

}

?>