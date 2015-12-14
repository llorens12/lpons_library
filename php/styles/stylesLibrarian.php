<?php

/**
 * Class stylesLibrarian, this class is static and contains all styles of user Librarian
 */
class stylesLibrarian
{

    /**
     * This method contains the style of the my profile menu.
     * @param string $nameUser *Description*: contains the name of the user.
     * @param $sid *Description*: contains the session id.
     * @return string *Description*: all style of my profile menu.
     */
    public static function menuTop($nameUser, $sid)
    {
        return stylesUser::menuTop($nameUser, $sid);
    }

    /**
     * This method contains all style of the content menu.
     * @param $sid *Description*: contains the session id.
     * @return string *Description*: all style of the content menu.
     */
    public static function menuContent($sid)
    {
        $Users    = "default";
        $Books    = "default";
        $Reserves = "default";

        $$_SESSION['menu'] = "primary active";


        return
        '
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="btn-group btn-menu sub-menu">
                    <a href="controller.php?method=showTableUsers'.$sid.'" class="btn btn-'.$Users.' btn-lg  dropdown-toggle" >
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

    /**
     * This method contains the form of the book.
     * @param array|string $data *Description*: contains or not all book data.
     * @return string *Description*: form with or not book data.
     */
    public static function formBook($data = "")
    {
        $defaults = array
        (
            "isbn"          => "",
            "title"         => "",
            "description"   => "",
            "summary"       => "",
            "author"        => "",
            "category"      => ""
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
            <div class="input-group" title="ISBN">
                <span class="input-group-addon icons"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" placeholder="ISBN" name="isbn" id="isbn-form" required="" value="'.$vars['isbn'].'">
            </div>
            <div class="input-group" title="Title">
                <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                <input maxlength="40" type="text" class="form-control" placeholder="Title" name="title" required="" value="'.$vars['title'].'">
            </div>
            <div class="input-group" title="Description">
                <span class="input-group-addon icons"><i class="fa fa-text-height"></i></span>
                <textarea maxlength="250" rows="2" class="form-control" placeholder="Desctiption..." name="description" required="">'.$vars['description'].'</textarea>
            </div>
            <div class="input-group" title="Summary">
                <span class="input-group-addon icons"><i class="fa fa-text-height"></i></span>
                <textarea maxlength="3000" rows="5" class="form-control" placeholder="Summary..." name="summary" required="">'.$vars['summary'].'</textarea>
            </div>
            <div class="input-group" title="Author book">
                <span class="input-group-addon icons"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" placeholder="Author" name="author" required="" value="'.$vars['author'].'">
            </div>
            <div class="input-group" title="Category">
                <span class="input-group-addon icons"><i class="fa fa-hashtag"></i></span>
                <input type="text" class="form-control" placeholder="Category: Action, Adventure, Comedy..." name="category" required="" value="'.$vars['category'].'">
            </div>
            <div class="input-group" title="Cover">
                <span class="input-group-addon icons"><i class="fa fa-file-image-o"></i></span>
                <input type="file" class="form-control" name="cover" '.$required.'>
            </div>
        ';
    }

    /**
     * This method contains the form of the book copy.
     * @param array|string $data *Description*: contains or not all book copy data.
     * @return string *Description*: form with or not book copy data.
     */
    public static function formCopy($data = "")
    {
        $New    = "";
        $Good   = "";
        $Bad    = "";
        $formId = "";

        if(isset($data['status']))
            $$data['status'] = "selected";


        if(isset($data['id']))
        {
            $formId .=
            '
                <div class="input-group">
                    <span class="input-group-addon icons" title="ID Copy"><i class="fa fa-hashtag"></i></span>
                    <input type="text" class="form-control" placeholder="ID Copy"   title="ID Copy" value="' . $data['id'] . '" disabled>
                </div>
            ';
        }


        return $formId.
        '
            <div class="input-group">
                <span class="input-group-addon icons" title="ISBN"><i class="fa fa-barcode"></i></span>
                <input type="text" class="form-control" placeholder="ISBN" name="book" required="" title="ISBN" value="'.$data['book'].'" disabled>
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

    /**
     * This method contains the form with edit an a user reserve.
     * @param array $data *Description*: contains all user reserve data.
     * @return string *Description*: form with all user reserve data.
     */
    public static function formEditUserReserve($data)
    {
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

    /**
     * This method contains the piece form to send reserve status
     * @param array $data *Description*: contains or not all reserve information.
     * @return string *Description*: the piece form to send reserve status.
     */
    public static function formUserReserveStatus($data = NULL)
    {
        $sent         = "";
        $received     = "";
        $showReceived = "";

        if($data != null)
        {
            if (!is_null($data['sent']))
                $sent = "checked disabled";

            if (!is_null($data['received']))
                $received = "checked disabled";

        }
        else
        {
            $received = "disabled";
            $showReceived = "hidden";
        }

        return
        '
            <div class="input-group" title="Book sent?">
                <span class="input-group-addon icons"><i class="fa fa-sign-out"></i></span>
                <div class="checkbox form-control commited-reserves"  '.$sent.'>
                    <label>
                        <input type="checkbox" name="sent" id="status-sent" value="'.date('Y-m-d').'" '.$sent.'>
                        Sent?
                    </label>
                </div>
            </div>
            <div class="input-group '.$showReceived.'" title="Book received?">
                <span class="input-group-addon icons"><i class="fa fa-sign-in"></i></i></span>
                <div class="checkbox form-control commited-reserves" '.$received.'>
                    <label>
                        <input type="checkbox" name="received" value="'.date('Y-m-d').'" '.$received.'>
                        Received?
                    </label>
                </div>
            </div>
        ';
    }

    /**
     * This method contains the all style to add a new user reserve.
     * @param array $data *Description*: contains the user data.
     * @param mysqli $books *Description*: contains the isbn and title of the all books.
     * @param bool $error *Description*: if is false, not show error and else show error.
     * @return string *Description*: all style form to add new user reserve.
     */
    public static function formAddReserve($data, $books, $error)
    {
        $hidden = "hidden";

        if($error)
            $hidden = "";


        $optionsBooks = "";

        while($currentBook = mysqli_fetch_assoc($books))
            $optionsBooks .= '<option VALUE="'.$currentBook['isbn'].'">'.$currentBook['isbn'].' '.$currentBook['title'].'</option>';


        return
        '
            <div class="row row-centered container-form" id="form-add-user-reserve">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 content-form">
                    <div class="inputs-content-form" id="form-user-reserve">
                            <h2>Add User Reserve</h2>

                            <div class="input-group" title="User">
                                <span class="input-group-addon icons"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" value="'.$data['Email'].'" id="reserve-user" title="User" disabled/>
                            </div>
                            <div class="input-group" title="Book">
                                <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                                <select class="form-control" id="select-book">
                                    '.$optionsBooks.'
                                </select>
                            </div>
                            <div class="input-group" title="Number days of reserve">
                                <span class="input-group-addon icons"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="totalDays" placeholder="Number days of reserve" class="form-control" />
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

    /**
     * This method contains the form to administrate user. (Edit my profile and edit existing user)
     * @param array $currentUser *Description*: contains all user data.
     * @return string form to edit my profile or custom user.
     */
    public static function formAdministrateUser($currentUser)
    {
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

        if($currentUser['typeUser'] != 'User')
            $Librarian = str_replace($currentUser['home'].'"', $currentUser['home'].'" selected', $Librarian);

        else
            $Librarian = "";

        return stylesUser::formAdministrateUser($currentUser, $Librarian);
    }

}