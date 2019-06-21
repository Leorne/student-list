<?php

class Validator
{
    //мужчина
    const GENDER_MALE = 'm';
    //женщина
    const GENDER_FEMALE = 'f';
    //местный
    const LOCAL_TYPE = 'l';
    //Не местный
    const NOT_LOCAL_TYPE = 'n';
    //уже внесен в БД
    const OLD_STUDENT = 'old';
    //новая запись в БД
    const NEW_STUDENT = 'new';

    //паттерны для регулярных выражений и HTML-шаблона
    const NAME_PATTERN = "[A-Za-z]{2,20}|[А-ЯЁа-яё]{2,20}";
    const SECOND_NAME_PATTERN = "[A-Za-z]{2,20}|[А-ЯЁа-яё]{2,20}";
    const MAIL_PATTERN = "[0-9a-zA-Z_\-\.]+@[a-zA-Z0-9_\-\.]+\.[A-Za-z]{2,5}";
    const POINTS_PATTERN = "[1-9][0-9]{0,3}";
    const GROUP_NAME_PATTERN = "[0-9A-Za-z]{2,5}";

    //Проверка имени и фамилии
    public static function checkName(string $name){
        $pattern = "~^".Validator::NAME_PATTERN."$~u";
        if (!preg_match($pattern, $name)){
            if ($error = Validator::onlyOneType($name)){
                return $error;
            }
            elseif($error = Validator::withoutNum($name)){
                return $error;
            }
            elseif($error = Validator::moreThenOneSpace($name)){
                return $error;
            }
            elseif ($error = Validator::withoutSymbols($name)){
                return $error;
            }
            else{
                return "Недопустимый формат данных.";
            }
        }
        else{
            return FALSE;
        }
    }

    //Проверка почты
    public static function checkMail(string $mail, $type){
        $pattern = "~^".Validator::MAIL_PATTERN."$~";
        if (!preg_match($pattern,$mail)){
            return 'Введите данные в верном формате.';
        }
        elseif(($type === Validator::NEW_STUDENT) && ($error = Validator::checkUniqueMail($mail))){
            return $error;
        }
        else{
            return FALSE;
        }
    }

    //Проверка названия группы
    public static function checkGroupName(string $group_name){
        $pattern = "~^".Validator::GROUP_NAME_PATTERN."$~";
        if (!preg_match($pattern,$group_name)){
            return 'Может содержать от 2 до 5 символов(латиница и числа).';
        }
        else{
            return FALSE;
        }
    }

    //Проверка баллов
    public static function checkPoints(string $points){
        if (($points < 1) || ($points>200)){
            return 'Максимальное колличество баллов - 200. Минимальное - 1.';
        }
        else{
            return FALSE;
        }
    }

    //Проверка места проживания
    public static function checkLocation(string $location){
        if (($location === Validator::LOCAL_TYPE) || ($location === Validator::NOT_LOCAL_TYPE)){
            return FALSE;
        }
        else{
            return 'Ошибка';
        }
    }
    //Проверка генгдера
    public static function checkGender(string $gender){
        if (($gender === Validator::GENDER_MALE) || ($gender === Validator::GENDER_FEMALE)){
            return FALSE;
        }
        else{
            return 'Ошибка';
        }
    }

    //Проверка даты рождения
    public static function checkBirth(string $birth){
        $limit_max = strtotime('100 years ago');
        $limit_min = strtotime('16 years ago');
        $birth = strtotime("$birth");
        if ($birth < $limit_max){
            return 'Возраст не может быть больше 100 лет.';
        }
        elseif ($birth > $limit_min){
            return 'Возраст не может быть меньше 16 лет';
        }
        else{
            return FALSE;
        }
    }

    //проверка почты на уникальность
    private static function checkUniqueMail($mail){
        $db = Db::getConnection();
        $result = $db->exec("SELECT 1 FROM student WHERE mail='$mail'");
        //вернулось не 0 строк
        if (!$result){
            return "Данный Mail уже зарегестрирован.";
        }
        //вернулось 0 строк
        else{
            return FALSE;
        }
    }


    //Функции для проверки имени
    private static function onlyOneType($string){
        if (preg_match("/[a-z][а-яё]|[а-яё][a-z]/ui", $string)){
            return 'Поле может содержать только кириллицу или латиницу.';
        }
        else{
            return FALSE;
        }
    }

    //проверка на содержание чисел
    private static function withoutNum($string){
        if(preg_match("/[0-9]/ui", $string)){
            return 'Поле не может содержать числа.';
        }
        else{
            return FALSE;
        }
    }

    //проверка на пробелы подряд
    private static function moreThenOneSpace($string){
        if(preg_match("/  /", $string)){
            return 'Поле не может содержать больше одного пробела подряд.';
        }
        else{
            return FALSE;
        }
    }

    //проверка на содержание символов
    private  static function withoutSymbols($string, $symbols = '[()./\&!@#$%^&*-_=+]'){
        if(preg_match("/$symbols/", $string)){
            return 'Недопустимые символы.';
        }
        else{
            return FALSE;
        }
    }
}