<?php
class Forms{
    static function login($error = ""){

        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        return
            '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Login</h3></label>
                <form class="container-box" action="php/startSession.php" method="POST">
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

    static function register(){
        return
            '
             <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8">
                    <div class="input-group">
                        <span class="input-group-addon glyphicon glyphicon-user icons"></span>
                        <input type="text" class="form-control" placeholder="Name" required="">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-users"></i></span>
                        <input type="text" class="form-control" placeholder="Surname" required="">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-at"></i></span>
                        <input type="email" class="form-control" placeholder="example@example.com" required="">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon glyphicon glyphicon-earphone icons"></span>
                        <input type="tel" class="form-control" placeholder="XXX-XX-XX-XX" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}-[0-9]{2}" required="">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                        <input type="password" class="form-control" placeholder="Password" required="">
                    </div>
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
}



class Menus{


    static function userConfig($nameUser){
        return '
            <div class="btn-group  sub-menu" id="nameUser">
                <spqn class="dropdown-toggle" >
                    ' .$nameUser. '
                    <span class="caret"></span>
                </spqn>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#">
                            My profile
                        </a>

                    </li>
                    <li>
                        <a href="#">
                            Configuration
                        </a>
                    </li>
                </ul>
            </div>

        ';
    }

    static function menuUser(){

    }

    static function menuLibrarian(){
        return '
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <a href="#" class="btn btn-lg btn-primary btn-menu">
                    Users
                </a>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 border-left border-right">
                <a href="#" class="btn btn-default btn-lg btn-menu">
                    Books
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a class="btn btn-default btn-lg  dropdown-toggle" >
                        Reserves
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#">
                                Action
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Another
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Something
                            </a>
                        </li>
                        <li role="separator" class="divider">

                        </li>
                        <li>
                            <a href="#">
                                Separated
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            ';
    }

    static function menuAdministrator(){
        return '
            <div class="row text-center content-menu" id="navigation">

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 ">
                    <button type="button" class="btn btn-primary">
                        Users
                    </button>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 ">
                    <button type="button" class="btn btn-default">
                        Books
                    </button>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 ">
                    <button type="button" class="btn btn-default">
                        Reserves
                    </button>
                </div>

            </div>

        ';

    }
}