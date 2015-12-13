<?php

class stylesAdmin
{
    public static function formUser($data = ""){

        $User = "";
        $Librarian = "";
        $Admin = "";

        if(isset($data['typeUser']))
            $$data['typeUser'] = "selected";


        return
            '
            <div class="input-group" title="Permision">
                <span class="input-group-addon icons"><i class="fa fa-book"></i></span>
                <select class="form-control" name="typeUser"">
                    <option value="User" '.$User.'>User</option>
                    <option value="Librarian" '.$Librarian.'>Librarian</option>
                    <option value="Admin" '.$Admin.'>Admin</option>
                </select>
            </div>
        '.
            stylesLibrarian::formAdministrateUser($data);

    }
}