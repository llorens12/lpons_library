<?php


class stylesUser{

    public static function table($data, $edit = false, $drop = false, $add = false, $objectAdd = "", $typeObject = "", $primaryKeys = "", $reserveDelimiter = false, $MAX_DAYS_RESERVE = 0, $sid = "")
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


            if($reserveDelimiter){
                $recived = $object['Received'];
                $sent = $object['Sent'];

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


            if($recived != ""
            && is_null($recived)
            && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )
            {
                $stateEdit = "";
            }
            elseif($recived != "")
            {
                $stateEdit = "not-active";
            }

            if($recived != ""
            && is_null($sent) && is_null($recived)
            && date_create($object['Start'])->diff(date_create($object['End']))->format("d") < $MAX_DAYS_RESERVE )
            {
                $stateDelete = "";
            }
            elseif($recived != "")
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

    public static function filterMenu($nameFilter, $filterDefault, $filterData, $placeHolderSearch, $method, $sid){

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
        return
            '
            <div id="options-reserves">

                <a href="controller.php?insert='.$hrefDefaultReserve.'" class="btn btn-primary" id="btn-reserve-20-days" title="Set reserve">
                Reserve '.$textBtnReserve.'
                </a>

                <button class="btn btn-default active" id="btn-personalized-reserve" title="Personalized my reserve">
                    <span>Personalized reserve
                        <span class="caret"></span>
                    </span>
                </button>

            </div>

            <div class="hidden" id="personalized-reserve">

                <form isbn="'.$isbn.'" action="controller.php?insert='.$hrefPersonalizedReserve.$sid.'" method="post" onsubmit="return checkReserveDisponibility()">

                <div class="input-group" id="start-date-reserve-personalized">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-plus-o"></i></span>
                    <input type="date" class="form-control" name="date_start" id="date-start" value="" required="">
                </div>
                <div class="input-group" id="finish-date-reserve-personalized">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                    <input type="date" class="form-control" name="date_finish" id="date-finish" value=""  required="">
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

                <form class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-form" action="controller.php?'.$sentenceType.'='.$sentence.$sid.'" method="POST">
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

    public static function formAdministrateUser($currentUser){

        $User =
            '
            <option value="showBooks">Books</option>
            <option value="showReserves">My Reserves</option>
        ';

        $User = str_replace($currentUser['home'].'"', $currentUser['home'].'" selected', $User);


        $Librarian =
            '
          <optgroup label="Users">
            <option value="showTableUsers">Info Users</option>
            <option value="showTableDefaulters">Info Defaulters</option>
            <option value="showAdministrateUsers">Administrate Users</option>
            <option value="showAddUser">Add User</option>
          </optgroup>

          <optgroup label="Books">
            <option value="showBooks">Show Books</option>
            <option value="showTableBooks">Info Books</option>
            <option value="showTableCopies">Info Copies</option>
            <option value="showAdministrateBooks">Administrate Books</option>
            <option value="showAdministrateCopies">Administrate Copies</option>
            <option value="showAddBook">Add Book</option>
          </optgroup>

          <optgroup label="Reserves">
            <option value="showReserves">My Reserves</option>
            <option value="showAdministrateReserves">Administrate Reserves</option>
          </optgroup>


        ';


        $Librarian = str_replace($currentUser['home'].'"', $currentUser['home'].'" selected', $Librarian);

        if($currentUser['typeUser'] == "Admin")
            $optionsHome = $Librarian;

        else
            $optionsHome = $$currentUser['typeUser'];

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

class stylesLibrarian
{

    public static function menuTop($nameUser, $sid){
        return stylesUser::menuTop($nameUser, $sid);
    }

    public static function menuContent($sid)
    {
        $Users = "default";
        $Books = "default";
        $Reserves = "default";

        $$_SESSION['menu'] = "primary active";


        return '
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a href="controller.php?method=showDefaulters'.$sid.'" class="btn btn-'.$Users.' btn-lg  dropdown-toggle" >
                        Users
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="controller.php?method=showTableUsers'.$sid.'">
                                Info Users
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showTableDefaulters'.$sid.'">
                                Info Defaulters
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="controller.php?method=showAdministrateUsers'.$sid.'">
                                Administrate Users
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showAddUser'.$sid.'">
                                Add User
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a  href="controller.php?method=showBooks'.$sid.'" class="btn btn-'.$Books.' btn-lg  dropdown-toggle" >
                        Books
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="controller.php?method=showBooks'.$sid.'">
                                Show Books
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showTableBooks'.$sid.'">
                                Info Books
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showTableCopies'.$sid.'">
                                Info Copies
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="controller.php?method=showAdministrateBooks'.$sid.'">
                                Administrate Books
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showAdministrateCopies'.$sid.'">
                                Administrate Copies
                            </a>
                        </li>
                        <li>
                            <a href="controller.php?method=showAddBook'.$sid.'">
                                Add Book
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a href="controller.php?method=showReserves'.$sid.'" class="btn btn-'.$Reserves.' btn-lg  dropdown-toggle" >
                        Reserves
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                         <li>
                            <a href="controller.php?method=showReserves'.$sid.'">
                                My reserves
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="controller.php?method=showAdministrateUsersReserves'.$sid.'">
                                Administrate Reserves
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            ';
    }

    public static function formBook($data = ""){
        $defaults = array
        (
            "isbn"          => "",
            "title"         => "",
            "description"   => "",
            "summary"       => "",
            "category"      => "",
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
                <span class="input-group-addon icons" title="ISBN"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" placeholder="ISBN" name="isbn" id="isbn-form" required="" title="ISBN" value="'.$vars['isbn'].'">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Title"><i class="fa fa-book"></i></span>
                <input maxlength="50" type="text" class="form-control" placeholder="Title" name="title" required="" title="Title" value="'.$vars['title'].'">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Description"><i class="fa fa-text-height"></i></span>
                <textarea maxlength="250" rows="2" class="form-control" placeholder="Desctiption..." name="description" required="" title="Description" >'.$vars['description'].'</textarea>
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Summary"><i class="fa fa-text-height"></i></span>
                <textarea maxlength="3000" rows="5" class="form-control" placeholder="Summary..." name="summary" required="" title="Summary">'.$vars['summary'].'</textarea>
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Category"><i class="fa fa-hashtag"></i></span>
                <input type="text" class="form-control" placeholder="Category: Action, Adventure, Comedy..." name="category" required="" title="Category" value="'.$vars['category'].'">
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Cover"><i class="fa fa-file-image-o"></i></span>
                <input type="file" class="form-control" name="img" '.$required.' title="Cover">
            </div>
        ';
    }

    public static function formCopy($data){

        $New  = "";
        $Good = "";
        $Bad  = "";
        $formId = "";

        if(isset($data['status'])){
            $$data['status'] = "selected";
        }


        if(isset($data['id']))
            $formId .=
            '
            <div class="input-group">
                <span class="input-group-addon icons" title="ID Copy"><i class="fa fa-hashtag"></i></span>
                <input type="text" class="form-control" placeholder="ID Copy"   title="ID Copy" value="'.$data['id'].'" disabled>
            </div>
            ';


        return
        $formId.
        '
            <div class="input-group">
                <span class="input-group-addon icons" title="ISBN"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" placeholder="ISBN" name="book" required="" title="ISBN" value="'.$data['isbn'].'" disabled>
            </div>
            <div class="input-group">
                <span class="input-group-addon icons" title="Status"><i class="fa fa-clock-o"></i></span>
                <select class="form-control" name="status" title="Status">
                    <option value="New" '.$New.'>New</option>
                    <option value="Good" '.$Good.'>Good</option>
                    <option value="Bad" '.$Bad.'>Bad</option>
                </select>
            </div>
        ';
    }

    public static function formEditUserReserve($data){

        return
            '
            <div class="input-group" title="User">
                <span class="input-group-addon icons"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" value="'.$data['user'].'" title="User" disabled/>
            </div>
            <div class="input-group" title="ID Copy" >
                <span class="input-group-addon icons"><i class="fa fa-hashtag"></i></span>
                <input type="text" class="form-control" value="'.$data['copybook'].'" disabled/>
            </div>'

        .stylesUser::formEditReserves($data)

        .stylesLibrarian::formUserReserveStatus($data);



    }

    public static function formUserReserveStatus($data = ""){

        $sent = "";
        $received = "";

        if($data != "")
        {
            if (!is_null($data['sent']))
                $sent = "checked disabled";

            if (!is_null($data['received']))
                $received = "checked disabled";

        }
        else
            $received = "disabled";

        return
            '
            <div class="input-group">
                <span class="input-group-addon icons"><i class="fa fa-sign-out"></i></span>
                <div class="checkbox form-control commited-reserves"  '.$sent.'>
                    <label>
                        <input type="checkbox" name="sent" id="status-sent" value="true" '.$sent.'>
                        Sent?
                    </label>
                </div>
            </div>
            <div class="input-group">
                <span class="input-group-addon icons"><i class="fa fa-sign-in"></i></i></span>
                <div class="checkbox form-control commited-reserves" '.$received.'>
                    <label>
                        <input type="checkbox" name="received" value="true" '.$received.'>
                        Received?
                    </label>
                </div>
            </div>
        ';
    }

    public static function formAddReserve($data, $books, $error){

        $hidden = "hidden";

        if($error){
            $hidden = "";
        }

        $optionsBooks = "";

        while($currentBook = mysqli_fetch_assoc($books))
        {
            $optionsBooks .= '<option VALUE="'.$currentBook['isbn'].'">'.$currentBook['isbn'].' '.$currentBook['title'].'</option>';
        }



        return
        '
            <div class="row row-centered container-form" id="form-add-user-reserve">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-form">
                    <div class="inputs-content-form">
                        <h2>Add User Reserve</h2>

                            <div class="input-group" title="User">
                                <span class="input-group-addon icons"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" placeholder="'.$data['Email'].'" title="User" disabled/>
                            </div>
                            <div class="input-group" title="Book">
                                <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                                <select class="form-control" id="select-book">
                                    '.$optionsBooks.'
                                </select>
                            </div>
                            <div class="input-group" title="Total days reserve">
                                <span class="input-group-addon icons"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="totalDays" class="form-control" />
                            </div>

                            '.stylesLibrarian::formUserReserveStatus().'

                    </div>
                    <label class="label label-danger '.$hidden.'" id="label-error-personalized-reserve">This option is not aviable</label>
                    <br>
                </div>
            </div>
        '.
        stylesUser::personalizedReserve();

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

    public static function formRegister($data = ""){

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
                    <input type="text" class="form-control" placeholder="Name" name="name" required="" value="'.$vars['name'].'">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-users"></i></span>
                    <input type="text" class="form-control" placeholder="Surname" name="surname" required="" value="'.$vars['surname'].'">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-at"></i></span>
                    <input type="email" class="form-control" placeholder="example@example.com" name="email" id="email" required="" value="'.$vars['email'].'">
                </div>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-earphone icons"></span>
                    <input type="tel" class="form-control" placeholder="XXX-XX-XX-XX" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}-[0-9]{2}" name="telephone" required="" value="'.$vars['telephone'].'">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="pwd" id="pwd" '.$required.'>
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
                    <input type="text" class="form-control" placeholder="Category: Action, Adventure, Comedy..." name="category" required="">
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
                    <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date_start"  required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-calendar-times-o"></i></span>
                    <input type="date" class="form-control" placeholder="dd/mm/aaaa" name="date_finish" required="">
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
                <div class="input-group">
                    <span class="input-group-addon icons"><i class="fa fa-paper-plane"></i></span>
                    <div class="checkbox form-control" >
                        <label>
                            <input type="checkbox" name="received" value="true">
                            Received?
                        </label>
                    </div>
                </div>
        ';
    }
}


