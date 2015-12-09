<?php
/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 06/12/2015
 * Time: 17:00
 */

$p = array
(
    "m" => "this m",
    "l" => "this l",
    "j" => "this j"
);

foreach($p as $key => $value){
    $$key = $value;
}
unset($p['pwd']);
echo $m."<br>".$l."<br>".$j;