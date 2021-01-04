<?php
/* session_start() pour ouvrir la page;

$db = new PDO('mysql:host=localhost;dbname=classes', 'root', ''); 

Ouverture "classique" de la session et chemin "classique" d'accès à la base */  

class UserPdo
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;

    // attributs de la classe (caractéristiques communes)

    private function connectdb()
    {
        return new PDO('mysql:host=localhost;dbname=classes;charset=utf8', 'root', '');
    }

    /* En ouvrant la session et en appellant la base par une fonction, au sein de la classe, cela permet de modifier
    l'accès à la base une seule fois, pour l'ensemble des pages dans laquelle la classe est utilsée */

    public function register($login, $password, $cpassword, $email, $firstname, $lastname)
    {
        $msg = '';
        $db = $this->connectdb();

        $login = trim(htmlspecialchars($login));
        $password = trim(htmlspecialchars($password));
        $cpassword = trim(htmlspecialhars($cpassword));
        $email = trim(htmlspecialchars($email));
        $firstname = trim(htmlspecialchars($firstname));
        $lastname = trim(htmlspecialchars($lastname));
       
        $query=$db->prepare("SELECT id FROM utilisateurs WHERE email = '$email'");
        $query->execute();
        $checkmail=$query->rowCount();

        if (!$checkmail)
        {
            $query=$db->prepare("SELECT id FROM utilisateurs WHERE login = ?");
            $query->execute(('login'));
            $checkuser=$query->rowCount();

            if (!$checkuser)
            {
                if ($password == $cpassword)
                {
                    $hashedpass = password_hash($password, PASSWORD_BCRYPT);
                    $query=$db->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?,)");
                    $query->execute(array($login, $password, $email, $firstame, $lastname));
                    $db = null;
                    //header('Location: login.php');
                }
                else
                {
                    $msg['password'] = "Les mots de passe ne correspondent pas";
                }
            }
            else
            {
                $msg['login'] = "Le login existe déjà";
            }
        }
        else
        {
            $msg['email'] = "L'adresse email est déjà utilisée";
        }

        return $msg;
    }

    public function connect($login, $password)
    {
        $error = false;
        $db = $this->connectdb();

        $login = trim(htmlspecialchars($login));
        $password = trim(htmlspecialchars($password));

        $query=$db->prepare("SELECT id FROM utilisateurs WHERE login = '$login'");
        $query->execute();
        $checkuser=$query->rowCount();

        if ($checkuser)
        {
            $query=$db->prepare("SELECT password FROM utilisateurs WHERE login = '$login'");
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            $hashedpass=$results->password;

            if (password_verify($hashedpass, $password))
            {
                $query=$db->prepare("SELECT * FROM utilisateurs WHERE login = '$login'");
                $query->execute();
                $result=$query->fetch(PDO::FETCH_OBJ);

                $this->id = $result->id;
                $this->login = $result->login;
                $this->firstname = $result->firstname;
                $this->lastname = $result->lastname;
                $this->email = $result->email;
            }
            else
            {
                $error = true;
            }
        }
        else
        {
            $error = true;
        }
        return $error;
    }

    public function disconnect()
    {
        session_destroy();
        //header('Location: index.php');
    }

    public function delete()
    {
        $db=new PDO('mysql:host=localhost;dbname=classes;charset=utf8', 'root', '');
        $query=$db->prepare('DELETE FROM utilisateurs WHERE login=?');
        $query->execute([$this->login]);
        $result=$query->fetch();
        $this->disconnect();
    }

    public function update ($login, $password, $email, $firstname, $lastname) 
    {
        $this->login=$login;
        $this->password=$password;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;

        $db=new PDO('mysql:host=localhost;dbname=classes;charset=utf8', 'root', '');
        $query=$db->prepare('UPDATE utilisateurs SET login=?, password=?, email=?, firstname=?, lastname=? WHERE id=?');
        $query->execute([$this->login, $this->password, $this->email, $this->firstname, $this->lastname, $this->id]);
        $result=$query->fetch();
        return $result;
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
        $db=new PDO('mysql:host=localhost;dbname=classes;charset=utf8', 'root', '');
        $query=$db->prepare('SELECT * FROM utilisateurs WHERE login=?');
        $query->execute([$this->login]);
        $result=$query->fetch();
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





