<?php

class Main_Controller
{
    //лимит вывода студентов на стрицу
    private $limit = 50;
    //количество студентов для текущих get-параметров
    private $countStudents;
    //количество страниц для текущих get-параметров
    private $countPages;
    //все параметры, включая limit
    private $parameters = array();


    public function __construct()
    {
        $parameters['limit'] = $this->limit;
        if (isset($_GET['search'])) {
            $parameters['search'] = $_GET['search'];
        }
        //количество студентов подходящих для Search-запроса
        $this->countStudents = Main::getCount($parameters);
        //количество страниц
        $this->countPages = ceil($this->countStudents / $this->limit);

        //какая страница выбрана
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            //если страница равна 0 или больше возможного кол-ва страниц, то присваиваем первую.
            if (($page <= 0) || ($page > $this->countPages)) {
                header("location: /error");
            }
        }
        //если страница не выбрана, то по умолчанию первая
        else{
            $page = 1;
        }
        $parameters['page'] = $page;
        //смещение. с какой записи начинать отбор информации для заданной страницы
        $offset = 0;
        for ($i = $page; $i > 1; $i--) {
            $offset += $this->limit;
        }
        $parameters['offset'] = $offset;

        //сортировка
        if (isset($_GET['by'])) {
            //атрибут сортировки
            $parameters['by'] = $_GET['by'];
            //тип сортировки ASC-DESC
            $parameters['as'] = $_GET['as'];
        }
        $this->parameters = $parameters;
    }

    //выделяем текст который искал пользователь
    private function markList(array $list){
        $search = "/{$this->parameters['search']}/ui";
        $i = 0;
        foreach ($list as $student){
            $student = preg_replace($search, '<mark class="select">$0</mark>', $student);
            $list[$i] = $student;
            $i++;
        }
        return $list;
    }



    //
    public function actionList($parameters)
    {
        //get-параметры
        $query = $parameters;
        //если осуществляется поиск данных
        if (isset($this->parameters['search'])) {
            //получаем массив студентов
            $list = Main::getSearchList($this->parameters);
            //
            $list = $this->markList($list);
        } else {
            // get all our students
            $list = Main::getList($this->parameters);
        }
        //view
        require_once(ROOT . '/views/main.html');
    }
}