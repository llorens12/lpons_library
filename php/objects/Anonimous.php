<?php

/* Tot el que un usuari no autentificat pugui realitzar anira aqui */

class Anonimous extends Template{

    use DBController;

    public function __construct()
    {
        parent::__construct();
        $this->login();
    }

    public function login($error = ""){

        $this->includeSection = false;
        $this->textButton     = "Register";
        $this->linkButton     = "controller.php?method=register";


        if(isset($_SESSION['menu']))
            $_SESSION['menu'] = "Log In";

        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        $this->content =
            '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Login</h3></label>
                <form class="container-box" action="controller.php?method=startSession" method="POST">
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
        $this->textButton     = "Log In";
        $this->linkButton     = "controller.php";

        if(isset($_SESSION['menu']))
            $_SESSION['menu'] = "Register";

        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        $this->content =
        '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8" action="controller.php?method=insertUser" method="POST" id="registerForm" onsubmit="return registerContent(event)">

                    <div id="error">
                    ' .$error. '
                    </div>
                    ' .Registers::register(). '

                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                        <input type="password" class="form-control" placeholder="Repeat Password" id="pwd1" required="">
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

    public function startSession($email, $pwd, $remember)
    {
        if (isset($_COOKIE['email'], $_COOKIE['pwd']))
        {
            $email = $_COOKIE['email'];
            $pwd = $_COOKIE['pwd'];
        }

        $user = $this->select("SELECT * FROM users WHERE email = '{$email}' AND pwd = '{$pwd}';")->fetch_assoc();
        $this->close();

        if (count($user) != 0)
        {
            $_SESSION['typeUser'] = $user['typeUser'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['home'] = $user['home'];


            if ($remember) {
                setcookie("email", $user['email'], time() + 7776000, "/");
                setcookie("pwd", $user['pwd'], time() + 7776000, "/");
            }

            return true;

        } else  return false;
    }

    public function insertUser($request)
    {
        $request['pwd']     = md5($request['pwd']);
        $request['typeUser'] = "user";
        $request['registered'] = date('Y-m-d');
        $request['home']= "controller.php?method=showBooks";


        if($this->insert("users",$request))
        {
            $this->close();
            $this->login();
        }
        else
        {
            $this->close();
            $this->register("Register error");
        }
    }
}

