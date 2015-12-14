<?php

/**
 * Class stylesAnonimous, this class is static and contains all styles of user Anonimous
 */
class stylesAnonimous
{
    /**
     * This method generate the style form login content.
     * @param string $error *Description*: contains the text if exists an error.
     * @return string *Description*: all style of content login form.
     */
    public static function contentLogin($error = "")
    {
        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        return
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

    /**
     * This method generate the style of user register content.
     * @param string $error *Description*: contains the text if exists an error.
     * @return string *Description*: all style of content user register form.
     */
    public static function contentRegister($error = "")
    {
        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        return
        '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8" action="controller.php?insert=insertUser" method="POST" id="registerForm" onsubmit="return checkRegisterContent(event)">

                    <div id="error">
                    ' .$error. '
                    </div>
                    ' .stylesAnonimous::formRegister(). '

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

    /**
     * This method contains the form of register user.
     * @param string $data *Description*: contains or not all user data.
     * @return string *Description*: form with or not user data.
     */
    public static function formRegister($data = "")
    {
        $defaults = array
        (
            "name"      => "",
            "surname"   => "",
            "email"     => "",
            "telephone" => "",
        );

        if($data != "")
        {
            $vars = $data;
            $required = "";
        }
        else
        {
            $vars = $defaults;
            $required = 'required=""';
        }

        return
        '
            <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-user icons"></span>
                <input type="text" class="form-control" placeholder="Name" name="name" required="" value="' .$vars['name']. '">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons"><i class="fa fa-users"></i></span>
                <input type="text" class="form-control" placeholder="Surname" name="surname" required="" value="' .$vars['surname']. '">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons"><i class="fa fa-at"></i></span>
                <input type="email" class="form-control" placeholder="example@example.com" name="email" id="email" required=""
                       value="' .$vars['email']. '">
            </div>
            <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-earphone icons"></span>
                <input type="tel" class="form-control" placeholder="XXX-XX-XX-XX" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}-[0-9]{2}"
                       name="telephone" required="" value="' .$vars['telephone']. '">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" placeholder="Password" name="pwd" id="pwd" ' .$required. '>
            </div>
        ';
    }
}