<?php

/* Tot el que un usuari no autentificat pugui realitzar anira aqui */

class Anonimous extends Template{

    use DBController;

    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }




    public function showLogin($error = ""){

        $this->includeSection = false;
        $this->textButton     = "Register";
        $this->linkButton     = "controller.php?method=showRegister";


        $this->setContent(
            stylesAnonimous::contentLogin($error)
        );
    }

    public function showRegister($error = "")
    {
        $this->includeSection = false;
        $this->textButton     = "Log In";
        $this->linkButton     = "controller.php?method=showLogin";

        $this->setContent(
            stylesAnonimous::contentRegister($error)
        );
    }


    public function startSession($request)
    {
        if (isset($_COOKIE['email'], $_COOKIE['pwd']))
        {
            $email = $_COOKIE['email'];
            $pwd   = $_COOKIE['pwd'];
        }
        else
        {
            $email = $request['email'];
            $pwd   = md5($request['pwd']);
        }

        $user = mysqli_fetch_assoc($this->select("SELECT * FROM users WHERE email = '".$email."' AND pwd = '".$pwd."'"));
        $this->close();

        if (count($user) != 0)
        {
            $_SESSION['typeUser'] = $user['typeUser'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['home'] = "controller.php?method=".$user['home'];


            if (isset($request['rememberMe']))
            {
                setcookie("email", $email, time() + 7776000, "/");
                setcookie("pwd", $pwd['pwd'], time() + 7776000, "/");
            }

            return true;

        } else  return false;
    }

    public function insertUser($request)
    {
        $request['pwd']         = md5($request['pwd']);
        $request['typeUser']    = "User";
        $request['registered']  = date('Y-m-d');
        $request['home']        = "showBooks";

        $this->close();

        return $this->insert("users",$request);
    }



    public function __toString()
    {
        if($this->getContent() == "")
            $this->showLogin();
        return utf8_encode($this->html());
    }
}

