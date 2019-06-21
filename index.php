<?php


//settings
ini_set('display_errors',1);
error_reporting(E_ALL);

//file connections
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Router.php');
require_once(ROOT.'/components/Db.php');
require_once(ROOT.'/config/autoload.php');
//компоненты
@spl_autoload_register(autoloadComponents);
//контроллеры
@spl_autoload_register(autoloadController);
//модели
@spl_autoload_register(autoloadModels);


//Call to router
$router = new Router();
$router->start();
