<?php

class stylesUser{

    public static function table($data, $edit = false, $drop = false, $add = false, $objectAdd = "", $typeObject = "", $primaryKeys = "", $reserveDelimiter = false, $showColumnSentReceived = false, $MAX_DAYS_RESERVE = 0, $sid = "")
    {
        $contentThead   = "<th>#</th>";
        $objectNumber   = 1;
        $contentTbody   = "";
        $thead          = true;
        $stateEdit      = "";
        $stateDelete    = "";
        $valueLink      = "";




        if($typeObject == "")
            $typeObject = "This";


        $theadOptions = function ($edit, $drop, $add)
        {

            $contentThead = "";

            if($add){
                $contentThead .=
                    "
                    <th>
                        Add
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

        $tbodyOptions = function ($edit, $drop, $add, $objectAdd, $typeObject, $valueLink, $stateEdit, $stateDelete, $sid){

            $contentTbody = "";

            if($add){
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showAdd'.$objectAdd.'&' . $valueLink . $sid.'" title="Add new '.$objectAdd.'">
                            <i class="fa fa-plus"></i>
                        </a>
                    </td>
                ';
            }


            if($edit) {
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?method=showEdit' . $typeObject . '&' . $valueLink . $sid.'" title="Edit" class="'.$stateEdit.'">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    </td>
                    ';
            }


            if($drop) {
                $contentTbody .=
                    '
                    <td>
                        <a href="controller.php?delete=setDelete' . $typeObject . '&' . $valueLink . $sid.'" title="Delete" class="'.$stateDelete.'">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                ';
            }

            return $contentTbody;
        };


        if(mysqli_num_rows($data) == 0){
            return "<h1 style='width: 100%; text-align: center'>".$typeObject." is empty</h1>";
        }

        while ($object = mysqli_fetch_assoc($data))
        {
            $contentTr = "";
            $recived = "";
            $sent = "";


            if($reserveDelimiter)
            {
                $recived = $object['Received'];
                $sent = $object['Sent'];
            }

            if($showColumnSentReceived )
            {
                unset($object['Received']);
                unset($object['Sent']);
            }

            foreach ($object as $column => $value)
            {
                if ($thead)
                    $contentThead .= "<th>" . $column . "</th>";

                if(is_null($value))
                    $value = "No";

                $contentTr      .= '<td title="'.$column.'">' . $value  . '</td>';
            }

            $thead = false;

            $contentTbody .=
                '    <tr>

                    <th scope="row">'
                . $objectNumber .
                '</th>'

                . $contentTr;


            if($reserveDelimiter
                && is_null($recived)
                && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )
            {
                $stateEdit = "";
            }
            elseif($reserveDelimiter)
            {
                $stateEdit = "not-active";
            }

            if($reserveDelimiter
                && is_null($sent) && is_null($recived)
                && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )
            {
                $stateDelete = "";
            }
            elseif($reserveDelimiter)
            {
                $stateDelete = "not-active";
            }

            $valueLink = "";

            if(is_array($primaryKeys)) {


                foreach ($primaryKeys as $singleKey) {
                    $valueLink .= str_replace(' ', '', $singleKey) . "=" . $object[$singleKey] . "&";
                }
                $valueLink = trim($valueLink, "&");
            }

            $contentTbody .= $tbodyOptions($edit,$drop, $add, $objectAdd, $typeObject, $valueLink, $stateEdit, $stateDelete, $sid);


            $contentTbody .=
                '
                  </tr>
                ';

            $objectNumber++;
        }


        $contentThead .= $theadOptions($edit, $drop, $add);

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

    public static function filterMenu($titleFilter, $nameFilter, $filterDefault, $filterData, $placeHolderSearch, $method, $sid){

        if($nameFilter == "Order by")
            $valueButtonFilter = "";

        else
            $valueButtonFilter = $nameFilter;

        $filter = "";

        foreach ($filterData as $data){

            $data = trim($data,"'");
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h1  id="titleFilterMenu">
                            '.$titleFilter.'
                        </h1>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="options-books">
                    <form id="form-filter-menu" action="controller.php?method='.$method.$sid.'" method="POST" >
                        <div class="btn-group sub-menu" id="btn-category">
                            <button class="btn btn-default active btn-md dropdown-toggle" name="btnCategory" value="'.$valueButtonFilter.'">
                                '.$nameFilter.'
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                                '.$filter.'

                            </ul>
                        </div>
                            <div class="input-group" id="books-search">
                                <input type="search" id="search" class="form-control" placeholder="'.$placeHolderSearch.'" required="" name="search" />
                                <span class="input-group-addon icons"><i class="fa fa-search"></i></span>
                            </div>
                        </form>
                    </div>
                ';
    }

    public static function book($book, $DEFAULT_DAYS_RESERVE, $sid){
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


                        '.stylesUser::personalizedReserve
            (
                "setInsertDefaultReserve&isbn=".$book["isbn"].$sid.'"',
                'setInsertPersonalizedReserve&isbn='.$book["isbn"].$sid,
                $DEFAULT_DAYS_RESERVE." days",
                $book["isbn"],
                $sid
            ).'

                        </div>
                    </div>
            ';
    }

    public static function personalizedReserve($hrefDefaultReserve = "", $hrefPersonalizedReserve = "", $textBtnReserve = "", $isbn = "", $sid = ""){

        $url = "controller.php?insert=";
        if($hrefDefaultReserve == "" && $hrefPersonalizedReserve == "")
            $url = "";

        return
            '
            <div id="options-reserves">

                <a href="'.$url.$hrefDefaultReserve.'" class="btn btn-primary" id="btn-reserve-20-days" title="Set reserve">
                Reserve '.$textBtnReserve.'
                </a>

                <button class="btn btn-default active" id="btn-personalized-reserve" title="Personalized my reserve">
                    <span>Personalized reserve
                        <span class="caret"></span>
                    </span>
                </button>

            </div>

            <div class="hidden" id="personalized-reserve">

                <form isbn="'.$isbn.'" action="'.$url.$hrefPersonalizedReserve.$sid.'" method="post" onsubmit="return checkReserveDisponibility()">

                <div class="input-group" id="start-date-reserve-personalized">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                    <input type="date" class="form-control current-date" name="date_start" id="date-start" value="" required="">
                </div>
                <div class="input-group" id="finish-date-reserve-personalized">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                    <input type="date" class="form-control  current-date" name="date_finish" id="date-finish" value=""  required="">
                </div>

                <label class="label label-danger hidden" id="label-error-personalized-reserve">The reserve is not available or already reserved book</label>
                <br>
                <button type="submit" class="btn btn-default">Reserve</button>
                </form>

            </div>
        ';
    }

    public static function books($book, $DEFAULT_DAYS_RESERVE, $sid){

        return
            '
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 show-books">
                <div>
                    <a href="controller.php?method=showBook&isbn='.$book["isbn"].$sid.'" title="Show details">
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
                            <a href="controller.php?insert=setInsertDefaultReserve&isbn='.$book["isbn"].$sid.'" class="btn btn-primary button-show-books" title="Reserve as soon as possible">Reserve '.$DEFAULT_DAYS_RESERVE.' days</a>
                        </div>
                    </a>
                </div>
            </div>
        ';

    }

    public static function menuTop($nameUser, $sid){
        return
            '
            <span class="dropdown-toggle">
                ' . $nameUser . '
                <span class="caret"></span>
            </span>
            <ul class="dropdown-menu">
                <li>
                    <a href="controller.php?method=showMyProfile'.$sid.'">
                        My profile
                    </a>

                </li>
            </ul>
        ';
    }

    public static function menuContent($sid){

        $Books = "default";
        $Reserves = "default";

        $$_SESSION['menu'] = "primary active";


        return
            '
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <a href="controller.php?method=showBooks'.$sid.'" class="btn btn-lg btn-'.$Books.' btn-menu" title="Show books">
                    Books
                </a>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 border-left" title="Show my reserves">
                <a href="controller.php?method=showReserves'.$sid.'" class="btn btn-'.$Reserves.' btn-lg btn-menu" title="Show reserves">
                    My Reserves
                </a>
            </div>
        ';
    }

    public static function contentFormEditReserves($reserve, $error, $sid){

        $hidden = "hidden";
        if($error){
            $hidden = "";
        }


        return
            '
            <div class="row row-centered container-form">

                <form class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-form" action="controller.php?update=setUpdateReserve&copyBook='.$reserve['copybook'].'&firstDateStart='.$reserve['date_start'].$sid.'" method="POST">
                    <div class="inputs-content-form">
                        <h2>Edit reserve</h2>
                        '.stylesUser::formEditReserves($reserve).'
                    </div>
                    <label class="label label-danger '.$hidden.'" id="label-error-personalized-reserve">The reserve is not available</label>
                    <br>
                    <div class="form-group btn-content-form">
                        <button type="submit" class="btn btn-default active" title="Save">
                            Save
                        </button>
                    </div>
                </form>
            </div>
                    ';
    }

    public static function formEditReserves($reserve){

        (!is_null($reserve['sent']))? $dateStart = "disabled" : $dateStart ="";
        (!is_null($reserve['received']))? $dateFinish = "disabled" : $dateFinish ="";

        return
            '
            <div class="input-group" title="ISBN">
                <span class="input-group-addon icons" title="ISBN"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" value="'.$reserve['isbn'].'" disabled >
            </div>
            <div class="input-group" title="Title">
                <span class="input-group-addon icons" title="Title"><i class="fa fa-book"></i></span>
                <input maxlength="50" type="text" class="form-control" value="'.$reserve['title'].'" disabled >
            </div>
            <div class="input-group" title="Author">
                <span class="input-group-addon icons" title="Author"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" value="'.$reserve['author'].'" disabled >
            </div>
            <div class="input-group" title="Category">
                <span class="input-group-addon icons" title="Category"><i class="fa fa-copyright"></i></span>
                <input type="text" class="form-control" value="'.$reserve['category'].'" disabled >
            </div>
            <div class="input-group" title="Date Start">
                <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                <input type="date" class="form-control" name="date_start" value="'.$reserve['date_start'].'" '.$dateStart.' >
            </div>
            <div class="input-group" title="Date Finish">
                <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                <input type="date" class="form-control" name="date_finish" value="'.$reserve['date_finish'].'" '.$dateFinish.'>
            </div>
                    ';
    }

    public static function contentForm($title, $form, $sid, $error, $sentenceType, $sentence){

        $hidden = "hidden";

        if($error){
            $hidden = "";
        }

        return
            '
            <div class="row row-centered container-form">

                <form class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-form" enctype="multipart/form-data" action="controller.php?'.$sentenceType.'='.$sentence.$sid.'" method="POST">
                    <div class="inputs-content-form">
                        <h2>'.$title.'</h2>
                        '.$form.'
                    </div>
                    <label class="label label-danger '.$hidden.'" id="label-error-personalized-reserve">This option is not aviable</label>
                    <br>
                    <div class="form-group btn-content-form">
                        <button type="submit" class="btn btn-default active" title="Save">
                            Save
                        </button>
                    </div>
                </form>
            </div>
                    ';
    }

    public static function formAdministrateUser($currentUser, $otherOptionsHome = ""){

        $User =
            '
            <option value="showBooks">Books</option>
            <option value="showReserves">My Reserves</option>
        ';

        $User = str_replace($currentUser['home'].'"', $currentUser['home'].'" selected', $User);




        if($otherOptionsHome != "")
            $optionsHome = $otherOptionsHome;

        else
            $optionsHome = $User;


        return
            stylesAnonimous::formRegister($currentUser).'


            <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-home icons"></span>
                    <select class="form-control" name="home">
                        '.$optionsHome.'
                    </select>
            </div>
        ';
    }
}