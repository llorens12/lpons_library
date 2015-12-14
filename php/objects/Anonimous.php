<?php

/**
 * Class Anonimous, this class extends of template and contains all options of not authenticated user.
 */
class Anonimous extends Template
{
    /**
     * Used to connect the Database.
     */
    use DBController;

    /**
     * Anonimous constructor.
     *
     * *Description*: call the parent construct and insert the user data.
     *
     * @param string $nameUser *Description*: contains the name of the user.
     * @param string $emailUser *Description*: contains the email of the user.
     * @param string $home *Description*: contains the url home of this user.
     * @param $sid  *Description*: contains the session id of the user.
     */
    public function __construct($nameUser, $emailUser, $home, $sid)
    {
        parent::__construct($nameUser, $emailUser, $home, $sid);
    }




    /**
     * This method insert the login content.
     * @param string $error *Description*: if exists error, print the error.
     * @void method.
     */
    public function showLogin($error = ""){

        $this->includeSection = false;
        $this->textButton     = "Register";
        $this->linkButton     = "controller.php?method=showRegister";


        $this->setContent(
            stylesAnonimous::contentLogin($error)
        );
    }

    /**
     * This method insert the register content.
     * @param string $error *Description*: if exists error, print the error.
     * @void method.
     */
    public function showRegister($error = "")
    {
        $this->includeSection = false;
        $this->textButton     = "Log In";
        $this->linkButton     = "controller.php?method=showLogin";

        $this->setContent(
            stylesAnonimous::contentRegister($error)
        );
    }



    /**
     * This method start the user session.
     * @param array $request *Description*: contains the data necessary to start session.
     * @return bool *Description*: return if start session or not.
     */
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

    /**
     * This method register a new user.
     * @param array $request *Description*: contains all data of the user.
     * @return bool *Description*: return if the insert are success or not.
     */
    public function insertUser($request)
    {
        $request['pwd']         = md5($request['pwd']);
        $request['typeUser']    = "User";
        $request['registered']  = date('Y-m-d');
        $request['home']        = "showBooks";

        $this->close();

        return $this->insert("users",$request);
    }




    /**
     * Override the parent method and this insert the content of web page.
     * @return string *Description*: return all content web page.
     */
    public function __toString()
    {
        if($this->getContent() == "")
            $this->showLogin();
        return utf8_encode($this->html());
    }
}

