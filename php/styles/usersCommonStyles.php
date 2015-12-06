<?php


class stylesUser{
    public static function table($data, $edit = false, $drop = false, $info = false, $typeObject = "", $primaryKey = "", $valuePrimaryKey = "", $reserveDelimiter = false, $MAX_DAYS_RESERVE = 0)
    {
        $contentThead   = "<th>#</th>";
        $objectNumber   = 1;
        $contentTbody   = "";
        $thead          = true;
        $stateEdit      = "";
        $stateDelete    = "";
        $valueLink      = "";



        $theadOptions = function ($edit,$drop, $info)
            {

                $contentThead = "";

                if($info){
                    $contentThead .=
                        "
                    <th>
                        Info
                    </th>
                ";
                }

                if($edit) {
                    $contentThead .=
                        "
                    <th>
                        Edit
                    </th>
                ";
                }

                if($drop) {
                    $contentThead .=
                        "
                    <th>
                        Remove
                    </th>
                ";
                }

                return $contentThead;
            };

        $tbodyOptions = function ($edit,$drop, $info, $typeObject, $valueLink, $stateEdit, $stateDelete){

            $contentTbody = "";

            if($info){
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showInfo' . $typeObject . '&primaryKey=' . $valueLink . '" title="Show more info">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
                ';
            }


            if($edit) {
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showEdit' . $typeObject . '&primaryKey=' . $valueLink . '" title="Edit" class="'.$stateEdit.'">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    </td>
                    ';
            }


            if($drop) {
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?delete=' . $typeObject . '&primaryKey=' . $valueLink . '" title="Delete" class="'.$stateDelete.'">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                ';
            }

            $contentTbody .=
                '
                  </tr>
                ';

            return $contentTbody;
        };

        ($valuePrimaryKey == "")? $valueLink = $valuePrimaryKey : $valueLink = "";


        while ($object = $data->fetch_assoc())
        {
            $contentTr = "";

            foreach ($object as $column => $value)
            {
                if ($thead)
                {
                    $contentThead .= "<th>" . $column . "</th>";
                }
                $contentTr      .= '<td title="'.$column.'">' . $value  . '</td>';
            }

            $thead = false;

            $contentTbody .=
                '    <tr>

                    <th scope="row">'
                . $objectNumber .
                '</th>'

                . $contentTr;


            ($reserveDelimiter
            && is_null($object['Received'])
            && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )?

                $stateEdit = ""
                    :
                $stateEdit = "not-active";



            ($reserveDelimiter
            && is_null($object['Sent']) && is_null($object['Received'])
            && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )?

                $stateDelete = ""
                    :
                $stateDelete = "not-active";


            ($primaryKey != "")?

                $valueLink = $primaryKey
                    :
                NULL;

            $contentTbody .= $tbodyOptions($edit,$drop, $info, $typeObject, $valueLink, $stateEdit, $stateDelete);

            $objectNumber++;
        }


        $contentThead .= $theadOptions($edit, $drop, $info);


        return '
        <table class="table table-striped">
            <thead>
                <tr>
                    ' . $contentThead . '
                </tr>
            </thead>
            <tbody>
                ' . $contentTbody . '
            </tbody>
        </table>
        ';
    }



    public static function filterMenu($nameFilter, $filterDefault, $filterData, $placeHolderSearch, $method, $sid){

        $filter = "";

        foreach ($filterData as $data){
            $filter .=
                '
                        <li>
                            <a href="controller.php?method='.$method.'&category='.$data.$sid.'">
                                '.$data.'
                            </a>
                        </li>
                    ';
        }


        $filter .=
            '
                <li role="separator" class="divider">
                <li>
                    <a href="controller.php?method='.$method.'&category=*'.$sid.'">
                        '.$filterDefault.'
                    </a>
                </li>
            ';

        return
            '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="options-books">
                        <div class="btn-group sub-menu" id="btn-category">

                            <button class="btn btn-default active btn-md dropdown-toggle" >
                                '.$nameFilter.'
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                '.$filter.'

                            </ul>

                        </div>


                        <form action="controller.php?method='.$method.$sid.'" method="POST" id="books-search">

                            <div class="input-group">

                                <input type="search" name="search" class="form-control" placeholder="'.$placeHolderSearch.'" required="">
                                <span class="input-group-addon icons"><i class="fa fa-search"></i></span>
                            </div>
                        </form>
                    </div>
                ';
    }

    public static function book($book, $DEFAULT_DAYS_RESERVE){
        return
            '
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="div-details-books">

                        <div id="img-details-books">

                            <img class="details-img" src="../img/books/'.$book["isbn"].'.jpg"/>

                            <label class="label label-default">ISBN: <label id="book-isbn">'.$book["isbn"].'</label></label>
                            <br><br>
                            <label class="label label-default">Category: '.$book["category"].'</label>
                            <br><br>
                            <label class="label label-default">Author: '.$book["author"].'</label>

                        </div>

                        <div id="text-details-books">


                            <h1>'.$book["title"].'</h1>


                            <p>'.$book["summary"].'</p>

                            <div id="options-reserves">

                                <a class="btn btn-primary" id="btn-reserve-20-days" title="Reserve as soon as possible">Reserve '.$DEFAULT_DAYS_RESERVE.' days</a>

                                <button class="btn btn-default active" id="btn-personalized-reserve" title="Personalized my reserve">
                                    <span>Personalized reserve
                                        <span class="caret"></span>
                                    </span>
                                </button>

                            </div>

                            <div class="hidden" id="personalized-reserve">

                                <form action="#" method="post" onsubmit="return checkReserveDisponibility()">

                                    <div class="input-group" id="start-date-reserve-personalized">
                                        <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                                        <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-start" id="date-start" required="">
                                    </div>
                                    <div class="input-group" id="finish-date-reserve-personalized">
                                        <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                                        <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-finish" id="date-finish" required="">
                                    </div>

                                    <label class="label label-danger hidden" id="label-error-personalized-reserve">The reserve is not available</label>
                                    <br>
                                    <button type="submit" class="btn btn-default">Reserve</button>
                                </form>

                            </div>

                        </div>
                    </div>
            ';
    }

    public static function books($book, $DEFAULT_DAYS_RESERVE){

        return
            '
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 show-books">
                <div>
                    <a href="controller.php?method=showBook&isbn='.$book["isbn"].'" title="Show details">
                        <div class="img-show-books">
                            <img src="../img/books/'.$book["isbn"].'.jpg" alt="Img of '.$book["title"].'">
                        </div>
                        <div class="data-show-books">
                            <div class="content-data-show-books">
                                <h2>'.$book["title"].'</h2>

                                <p class="listado">'.$book["description"].'</p>
                                <br>
                                <label class="label label-default">Category: '.$book["category"].'</label>
                                <br><br>
                                <label class="label label-default">Author: '.$book["author"].'</label>

                            </div>
                            <a href="#" class="btn btn-primary button-show-books" title="Reserve as soon as possible">Reserve '.$DEFAULT_DAYS_RESERVE.' days</a>
                        </div>
                    </a>
                </div>
            </div>
        ';

    }

    public static function menuTop($nameUser){
        return
            '
            <span class="dropdown-toggle">
                ' . $nameUser . '
                <span class="caret"></span>
            </span>
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
        ';
    }

    public static function menuContent($sid){

        $books = "default";
        $reserves = "default";

        switch ($_SESSION['menu']) {

            case "Books":

                $books = "primary active";
                break;

            case "Reserves":

                $reserves = "primary active";
                break;
        }

        return
            '
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a href="controller.php?method=showBooks'.$sid.'" class="btn btn-lg btn-'.$books.' btn-menu" title="Show books">
                    Books
                </a>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 border-left" title="Show my reserves">
                <a href="controller.php?method=showReserves'.$sid.'" class="btn btn-'.$reserves.' btn-lg btn-menu" title="Show reserves">
                    My reserves
                </a>
            </div>
        ';
    }
}

class stylesAnonimous{

    public static function contentLogin($error){
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

    public static function contentRegister($error){
        if($error != "")
            $error = '<h5><span class="label label-danger">'.$error.'</span></h5>';

        return
            '
            <div class="col-lg-3 col-md-6 col-sm-9 col-xs-12 col-centered box">
                <label class="box-tittle"><h3>Register</h3></label>

                <form class="container-box" accept-charset="UTF-8" action="controller.php?insert=user" method="POST" id="registerForm" onsubmit="return registerContent(event)">

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

    public static function formRegister(){
        return
            '
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user icons"></span>
                    <input type="text" class="form-control" placeholder="Name" name="name" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-users"></i></span>
                    <input type="text" class="form-control" placeholder="Surname" name="surname" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-at"></i></span>
                    <input type="email" class="form-control" placeholder="example@example.com" name="email" id="email" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-earphone icons"></span>
                    <input type="tel" class="form-control" placeholder="XXX-XX-XX-XX" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}-[0-9]{2}" name="telephone" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="pwd" id="pwd" required="">
                </div>
            ';
    }
}


class Registers
{

    static function register()
    {





    }

    static function book(){
        return
        '
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-barcode"></i></span>
                    <input type="text" class="form-control" placeholder="ISBN" name="ISBN" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                    <input maxlength="50"  type="text" class="form-control" placeholder="Title" name="title" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-text-height"></i></span>
                    <textarea maxlength="250" rows="2" class="form-control" placeholder="Desctiption..." name="description" required=""></textarea>
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-text-height"></i></span>
                    <textarea maxlength="3000" rows="5" class="form-control" placeholder="Summary..." name="summary" required=""></textarea>
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-hashtag"></i></span>
                    <input type="text" class="form-control" placeholder="Category: Action, Adventure, Comedy..." name="summary" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-file-image-o"></i></span>
                    <input type="file" class="form-control" name="img" required="">
                </div>
        ';
    }
    static function reserves(){
        return
        '
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-user"></i></span>
                    <select class="form-control" name="user">
                        <option VALUE="abc"> ABC</option>
                        <option VALUE="def"> def</option>
                        <option VALUE="hij"> hij</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                    <select class="form-control" name="book">
                        <option VALUE="abc"> ABC</option>
                        <option VALUE="def"> def</option>
                        <option VALUE="hij"> hij</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                    <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-start"  required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                    <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date-finish" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-paper-plane"></i></span>
                    <div class="checkbox form-control" id="commited-reserves">
                        <label>
                            <input type="checkbox" name="committed" value="true">
                            Committed?
                        </label>
                    </div>
                </div>
        ';
    }
}


class Menus
{


    static function userConfig($nameUser)
    {
        return '
            <div class="btn-group  sub-menu" id="nameUser">
                <span class="dropdown-toggle" >
                    ' . $nameUser . '
                    <span class="caret"></span>
                </span>
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

    static function menuUser()
    {

    }

    static function menuLibrarian()
    {
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

    static function menuAdministrator()
    {
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