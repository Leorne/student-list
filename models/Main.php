<?php

class Main
{
    //для проверки Order By
    private static $columnNames = array('name','second_name','group_name','points');

    // Количество строк соответствующих параметру (если он есть)
    public static function getCount($parameters){
        $db = Db::getConnection();
        //если пользователь выполнил поиск
        if (isset($parameters['search'])){
            $search = $parameters['search'];
            $search = "%$search%";
            //подготовили
            $count = $db->prepare('SELECT count(*) '
                                            .'FROM student '
                                            ."WHERE name LIKE :search OR "
                                            ."second_name LIKE :search OR "
                                            ."group_name LIKE :search OR "
                                            ."points LIKE :search");
            //связали параметры
            $count->bindValue(':search', $search);
            //выполнили запрос
            $count->execute();
            //получили количество строк
            $count = $count->fetchColumn(0);
        }
        //если пользователь ничего не ищет
        else{
            $count = $db->query('SELECT count(*) FROM student')->fetchColumn();
        }
        return $count;
    }


    //возвращение массива с результирующего набора вида PDO-обьекта
    private static function defineArray($result):array{
        $list = array();
        if ($result === false){
            $errorSQL = "SQL Error: ". $result->errorCode();
            echo $errorSQL;
        }
        else{
            $i=0;
            while ($row = $result->fetch()) {
                $list[$i]['id'] = $row['id'];
                $list[$i]['name'] = $row['name'];
                $list[$i]['second_name'] = $row['second_name'];
                $list[$i]['group_name'] = $row['group_name'];
                $list[$i]['points'] = $row['points'];
                $i++;
            }
        }
        return $list;
    }


    //получить данные из БД
    public static function getList($parameters){
        $db = Db::getConnection();
        $limit = $parameters['limit'];
        $offset = (isset($parameters['offset']))? $parameters['offset']:0;
        $by = (isset($parameters['by']))?$parameters['by']:'points';
        $as = (isset($parameters['by']))?$parameters['as']:'desc';
        if (!in_array($by,self::$columnNames)){
            $by = 'points';
        }
        if (!in_array($as, array('asc','desc'))){
            $as = 'desc';
        }
        $result = $db->query('SELECT id,name,second_name,group_name,points '
                                        .'FROM student '
                                        ."ORDER BY $by $as "
                                        ."LIMIT $limit OFFSET $offset");
        $list = Main::defineArray($result);

        return $list;
    }

   // получить данные из БД с поиском
    public static function getSearchList($parameters){
        $db = Db::getConnection();
        $search = $parameters['search'];
        $limit = $parameters['limit'];
        $search = "%$search%";
        $offset = (isset($parameters['offset']))? $parameters['offset']:0;
        $by = (isset($parameters['by']))?$parameters['by']:'points';
        $as = (isset($parameters['by']))?$parameters['as']:'desc';
        if (!in_array($by,self::$columnNames)){
            $by = 'points';
        }
        if (!in_array($as, array('asc','desc'))){
            $as = 'desc';
        }
        $result = $db->prepare('SELECT id,name,second_name,group_name,points '
                                        .'FROM student '
                                        ."WHERE name LIKE :search OR "
                                        ."second_name LIKE :search OR "
                                        ."group_name LIKE :search OR "
                                        ."points LIKE :search "
                                        ."ORDER BY $by $as "
                                        ."LIMIT $limit OFFSET $offset");

        $result->bindValue(':search',$search);
        $result->execute();
        $list = Main::defineArray($result);

        return $list;
    }


}