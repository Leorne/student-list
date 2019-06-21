<?php


class Student
{
    //тип студента
    private $type;
    //данные студента
    private $data = array();
    //ошибки ввода данных
    private $errors = array();

    function __construct(array $data, string $type = Validator::OLD_STUDENT){
        $this->type = $type;
        //определение данных студента

        //если TRUE, то Валидатор нашел ошибку
        if ($this->setName($data['name'])){
            //определяем ошибку
            $errors['name'] = $this->setName($data['name']);
        }
        if ($this->setSecondName($data['second_name'])){
            $errors['second_name'] = $this->setSecondName($data['second_name']);
        }
        if ($this->setMail($data['mail'])){
            $errors['mail'] = $this->setMail($data['mail']);
        }
        if ($this->setGender($data['gender'])){
            $errors['gender'] = $this->setGender($data['gender']);
        }
        if ($this->setGroupName($data['group_name'])){
            $errors['$group_name'] = $this->setGroupName($data['$group_name']);
        }
        if ($this->setPoints($data['points'])){
            $errors['points'] = $this->setPoints($data['points']);
        }
        if ($this->setLocation($data['location'])){
            $errors['location'] = $this->setLocation($data['location']);
        }
        if ($this->setBirth($data['birth'])){
            $errors['birth'] = $this->setBirth($data['birth']);
        }
        if (isset($errors)){
            $this->errors = $errors;
        }
    }



    //Проверяет и устанавливает имя, обращаясь к валидатору
    //если есть ошибка - возвращает текст ошибки, в обратном случае - false
    private function setName(string $name){
        if (($error = Validator::checkName($name)) != null){
            return $error;
        }
        else{
            $this->data['name'] = $name;
            return FALSE;
        }
    }


    //Проверяет и устанавливает фамилию, обращаясь к валидатору
    private function setSecondName(string $second_name){
        if (($error = Validator::checkName($second_name)) != NULL){
            return $error;
        }
        else{
            $this->data['second_name'] = $second_name;
            return FALSE;
        }
    }

    //Проверяет и устанавливает почту, обращаясь к валидатору
    private function setMail(string $mail){
        if (($error = Validator::checkMail($mail, $this->type))){
            return $error;
        }
        else{
            $this->data['mail'] = $mail;
            return FALSE;
        }
    }

    //Проверяет и устанавливает пол, обращаясь к валидатору
    private function setGender(string $gender){
        if (($error = Validator::checkGender($gender))){

        }
        else{
            $this->data['gender'] = ($gender == Validator::GENDER_MALE)? Validator::GENDER_MALE : Validator::GENDER_FEMALE;
            return FALSE;
        }
    }

    //Проверяет и устанавливает номер группы, обращаясь к валидатору
    private function setGroupName(string $group_name){
        if (($error = Validator::checkGroupName($group_name))){
            return $error;
        }
        else{
            $this->data['group_name'] = $group_name;
            return FALSE;
        }
    }

    //Проверяет и устанавливает количество баллов, обращаясь к валидатору
    private function setPoints(string $points){
        if (($error = Validator::checkPoints($points))){
            return $error;
        }
        else{
            $this->data['points'] = $points;
            return FALSE;
        }
    }

    //Проверяет и устанавливает место проживания, обращаясь к валидатору
    private function setLocation(string $location){
        if ($error = Validator::checkLocation($location)){
            return $error;
        }
        else{
            $this->data['location'] = ($location == Validator::LOCAL_TYPE)? Validator::LOCAL_TYPE : Validator::NOT_LOCAL_TYPE;
            return FALSE;
        }
    }

    //Проверяет и устанавливает дату рождения, обращаясь к валидатору
    private function setBirth(string $birth){
        if (($error = Validator::checkBirth($birth))){
            return $error;
        }
        else{
            $this->data['birth'] = $birth;
            return FALSE;
        }
    }

    //вернуть массив ошибок
    public function getErrors(){
        return $this->errors;
    }

    //вернуть данные пользователя
    public function getData(){
        return $this->data;
    }
}