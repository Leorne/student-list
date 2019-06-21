<?php

function autoloadComponents($class){
    $path = ROOT."/components/$class.php";
    if(file_exists($path)){
        require_once $path;
    }
}

function autoloadController($class)
{
    $path = ROOT . "/controller/$class.php";
    if (file_exists($path)) {
        require_once($path);
    }
}

function autoloadModels($class){
    $path = ROOT."/models/$class.php";
    if(file_exists($path)){
        require_once $path;
    }
}
