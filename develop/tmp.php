<?php
/**
 * Created by PhpStorm.
 * User: Lorenzo
 * Date: 06/12/2015
 * Time: 17:00
 */

class p{
    public function l($d){
        echo "antes de llamarlo <br>";



        function m(){
            echo parent::d;
        };



        call_user_func("m");

    }
}

$f = new p();

$f->l("d");