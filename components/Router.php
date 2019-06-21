<?php

class Router{
	private $routes;

	//Получили маршруты
    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }

    //Вернуть запрашиваемый URI
    private function getURI():string{
		if(!empty($_SERVER['REQUEST_URI'])){
		    return trim($_SERVER['REQUEST_URI'], '/');
        }
	}

    //Проверяем есть ли в маршрутах запрашиваемый URI. Получаем имена Контроллера
    //и метода
	private function checkURI(string $uri){
        //Отделили GET-запрос от пути
        $uri=parse_url($uri);
        //GET-запрос
        $query = (isset($uri['query']))? $uri['query']: null;
        //$query = $uri['query'];
        //путь к контроллеру/методу
        $uri = $uri['path'];
        //Для каждого маршрута
        foreach ($this->routes as $uriPattern => $path){
            //проверяем совпадение маршрута($uriPattern) и пути(uri) из URI
            if (preg_match("!$uriPattern!",$uri)){
                //
                //$path = preg_replace("!$uriPattern!", $path, $uri);


                $segments = explode('/',$path);

                //Имя контроллера
                $controllerName = array_shift($segments);
                //Имя метода
                $actionName = array_shift($segments);
                //Дополнительные параметры
                $parameters = $segments;
                //GET запрос
                $parameters['query'] = $query;
                $controllerName = ucfirst($controllerName)."_Controller";
                $actionName = 'action'.ucfirst($actionName);
                return array(
                  'controller' => $controllerName,
                    'action' => $actionName,
                    'parameters' => $parameters);
                //
                break;
            }
        }
        //Если Контроллер и Метод не были найдены, возвращаем Error
        if (!isset($controllerName)){
            return array(
                'controller' => 'Error_Controller',
                'action' => 'actionError',
                'parameters' => array(),
            );
        }
    }

	public function start(){
        //get URI
        $uri = $this->getURI();

        //check exist of URI in routes
        $names = $this->checkURI($uri);
        $controllerName = $names['controller'];
        $actionName = $names['action'];
        $parameters = $names['parameters'];


        //include controller Class
        $controllerPath = ROOT."/controller/${controllerName}.php";
        if (file_exists($controllerPath)){
            //create new Controller and call to Action
            $controllerObject = new $controllerName();
            $result = call_user_func_array(array($controllerObject,$actionName), $parameters);
        }
    }
}