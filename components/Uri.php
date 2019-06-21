<?php

//работа с
class Uri
{
    //меняет значение переменной Page в get-запросе
    public static function setPageUri($query, $i){
        parse_str($query, $parameters);
        $parameters['page'] = $i;
        $uri = http_build_query($parameters);
        echo "?$uri";
    }

    //выводит get-запрос для сортировки
    public static function setOrderUri($query, string $columnName){
        parse_str($query, $parameters);
        //если список отсортирован по заданному столбцу
        if(isset($parameters['by']) && ($parameters['by'] == $columnName)){
            //меняем тип сортировки ASC-DESC
            $parameters['as'] = ($parameters['as'] == 'desc') ? 'asc':'desc';
        }
        //если список сортируется впервые
        else{
            //атрибут сортировки
            $parameters['by'] = $columnName;
            //тип сортировки
            $parameters['as'] = 'desc';
        }
        //собираем get-запрос и выводим
        $query = http_build_query($parameters);
        echo "?$query";
    }

    //возвращает тип сортировки
    public static function getOrderCol($query, string $columnName){
        parse_str($query, $parameters);
        if (isset($parameters['by']) && ($parameters['by'] == $columnName)) {
            return $parameters['as'];
        }
        else{
            return FALSE;
        }
    }
}