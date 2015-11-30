<?php
include_once "../styles/Template.php";


/* Tot el que un usuari no autentificat pugui realitzar anira aqui */

class Anonimous extends Template{

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

        $this->setContent(
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
            ');
    }

    public function register()
    {
        $this->includeSection = false;
        $this->spanUser       = "";
        $this->textButton     = "Log In";
        $this->linkButton     = "index.php";


        $this->setContent(
        '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8">

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
        ');
    }
}