<?php
include_once "../styles/Template.php";
include_once "../trait/DBController.php";


/* Tot el que un usuari no autentificat pugui realitzar anira aqui */

class Anonimous extends Template{

    use DBController;

    public function __construct()
    {
        parent::__construct();

    }

    public function login($error = ""){

        $this->includeSection = false;
        $this->spanUser       = "";
        $this->textButton     = "Register";
        $this->linkButton     = "index.php?register=''";


        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        $this->content =
            '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Login</h3></label>
                <form class="container-box" action="Anonimous.php?method=startSession" method="POST">
                    ' .$error. '
                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-at"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="example@example.com" required="">

                    </div>
                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                        <input type="password" name="pwd" class="form-control" placeholder="Password" required="">
                    </div>
                    <div class="input-group" id="rememberMe">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="rememberMe" value="remember"> Remember me
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="btn-signin">
                        <button type="submit" class="btn btn-default" >
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
            ';
    }

    public function register($error = "")
    {
        $this->includeSection = false;
        $this->spanUser       = "";
        $this->textButton     = "Log In";
        $this->linkButton     = "index.php";

        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        $this->content =
        '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8">

                    ' .$error. '
                    ' .Registers::register(). '

                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                        <input type="password" class="form-control" placeholder="Repeat Password" required="">
                    </div>
                    <div class="form-group" id="btn-register">
                        <button type="submit" class="btn btn-default">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        ';
    }

    public function startSession($email, $pwd){

        $user = $this->select("SELECT * FROM users WHERE email = '{$email}' AND pwd = '{$pwd}';")->fetch_assoc();

        if (count($user) != 0) {

            session_cache_limiter('nocache,private');
            session_start();
            $user = $user->fetch_assoc();
            $_SESSION['typeUser'] = $user['typeUser'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['home'] = $user['home'];


            if (isset($_REQUEST['rememberMe']) && $_REQUEST['rememberMe'] == "remember") {
                setcookie("email", $user['email'], time() + 7776000, "/");
                setcookie("pwd"  , $user['pwd']  , time() + 7776000, "/");
            }


            //The htmlspecialchars() is used to prevent attacks related XSS
            header($_SESSION['home'] . '?' . htmlspecialchars(SID));

        } else  $this->login("Incorrect E-mail or Password");

    }

    public function insertUser($request)
    {
        $request['pwd']     = md5($request['pwd']);
        $request['typeUser'] = "user";
        $request['registered'] = date('Y-m-d');
        $request['home']= "";


        if($this->insert("users",$request))
        {
            $this->login();
        }
        else
        {
            $this->register("<h2>Insert error</h2>");
        }
    }
}

