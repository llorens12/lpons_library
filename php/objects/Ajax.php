<?php

class Ajax{
    use DBController;

    public function email($email){
        $answer = $this->select("select * from users WHERE email='".$email."'")->fetch_assoc();
        $this->close();

        if(count($answer) == 0)
            echo "true";
        else echo "false";

    }
}