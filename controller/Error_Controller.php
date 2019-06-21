<?php

class Error_Controller{

    public static function actionError(){
        header("HTTP/1.0 404 Not Found");
        require_once(ROOT . '/views/error.html');
    }
}