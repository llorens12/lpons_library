<?php
include_once "php/abstracts/Controller.php";


function getTable($data,$destination)
{
    /**
     * This save the <thead> content
     */
    $contentTheadTr="<th>#</th>";

    /**
     * This save the number of current object
     */
    $objectNumber = 0;

    /**
     * This save the <tbody> content
     */
    $contentTbody="";

    /**
     * This is a sentinel, controls if it has been inserted the <thead>
     */
    $thead=true;


    /**
     * Keeps track of each row
     */
    while($object = $data->fetch_assoc())
    {
        $contentTr="";
        foreach($object as $column => $value)
        {
            if($thead) $contentTheadTr .= "<th>".$column."</th>";
            $contentTr .= "<td>".$value."</td>";

        }
        $thead = false;
        $contentTbody .=
            '<tr>'.

                '<th scope="row">'
                .$objectNumber.
                '</th>'

                .$contentTr.

                '<td>
                    <a href="#">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                </td>

                <td>
                    <a href="#">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </td>
            </tr>';
        $objectNumber++;
    }

    return '
        <table class="table table-striped">
            <thead>
                <tr>
                    '.$contentTheadTr.'
                    <th>
                        Edit
                    </th>
                    <th>
                        Remove
                    </th>
                </tr>
            </thead>
            <tbody>
                '.$contentTbody.'
            </tbody>
        </table>
    ';
}




$controller = new Controller();

$data = $controller->select("select * from users");

echo getTable($data);


/*while($row = $data->fetch_assoc()){
    foreach($row as $key => $value){
        echo $key." = ".$value."  ";
    }
    echo "</br>";
}*/





/*
session_cache_limiter ('nocache,private');
session_start();



if(isset($_COOKIE['email'], $_COOKIE['pwd'])){
    echo "Existen las cookies y son: </br></br>";
    echo "email = ".$_COOKIE['email']."</br>";
    echo "email = ".$_COOKIE['pwd']."</br>";

}

echo "</br></br></br></br>";

if(isset($_SESSION['user'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home'])){
    echo "Existe la session: </br></br>";
    echo "user = ".$_SESSION['user']."</br>";
    echo "typeUser = ".$_SESSION['typeUser']."</br>";
    echo "name = ".$_SESSION['name']."</br>";
    echo "home = ".$_SESSION['home']."</br>";

}*/