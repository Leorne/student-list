<?php

class Profile_Controller
{
    //const STATUS_OK = 'Готово!';
    const STATUS_ADDED = 'Данные добавлены в список.';
    const STATUS_UPDATED = 'Ваши данные были обновленыю';
    const STATUS_ERROR = 'Введите данные в корректном формате!';
    const ACTION_UPDATE = 'Вы можете обновить свои данные.';
    const ACTION_ADD = 'Введите данные для добавления.';


    public function actionData()
    {
        session_start();
        //если в cookie есть установлены данные о студенте
        if (isset($_COOKIE['data'])){
            $oldData = $_COOKIE['data'];
            $action = self::ACTION_UPDATE;
            //пользователь обновляет существующие данные
            if (isset($_POST['data'])){
                $newData = $_POST['data'];
                //новый обьект студента, в котором будут проводиться валидация данных
                $student = new Student($newData, Validator::OLD_STUDENT);
                if ($student->getErrors() == null){
                    //обновляем cookie
                    foreach ($newData as $key => $value){
                        setcookie("data[$key]",$value,strtotime("10 years"));
                    }
                    //обновляем значение в БД
                    Profile::updateValues($newData, $oldData['mail']);
                    //данные которые будут выводиться в полях при загрузке страницы
                    $_SESSION['currentData'] = $newData;
                    $_SESSION['status'] = self::STATUS_UPDATED;

                    header ("location: $_SERVER[REQUEST_URI]");
                }
                else{
                    //
                    $_SESSION['currentData'] = $newData;
                    $_SESSION['errors'] = $student->getErrors();
                    $_SESSION['status'] = self::STATUS_ERROR;
                    header ("location: $_SERVER[REQUEST_URI]");
                }
            }
            elseif (isset($_SESSION['currentData'])){
                $currentData = $_SESSION['currentData'];
                $status = $_SESSION['status'];

                if(isset($_SESSION['errors'])){
                    $errors = $_SESSION['errors'];
                    unset($_SESSION['errors']);
                }
                unset($_SESSION['status']);
                unset($_SESSION['currentData']);
            }
            else{
                //данные которые будут выводиться в полях при загрузке страницы
                $currentData = $oldData;
            }
        }
        //если не установлены
        else{
            //если отправлены данные методом POST
            $action = self::ACTION_ADD;
            if (isset($_POST['data'])) {
                $newData = $_POST['data'];
                //обьект студента
                $student = new Student($newData);
                //проверяем массив с ошибками.
                //если в массиве есть ошибки, то данные были введены не верно
                if ($student->getErrors() == null) {
                    //задаем cookie
                    foreach ($newData as $key => $value){
                        $result = setcookie("data[$key]",$value,strtotime("10 years"));
                    }
                    //
                    $_SESSION['currentData'] = $newData;
                    $_SESSION['status'] = self::STATUS_ADDED;
                    //заносим данные в БД
                    Profile::addToBase($student->getData());
                    header ("location: $_SERVER[REQUEST_URI]");

                }
                //если есть ошибки
                else {
                    $_SESSION['currentData'] =$newData;
                    $_SESSION['errors'] = $student->getErrors();
                    $_SESSION['status'] = self::STATUS_ERROR;
                    header ("location: $_SERVER[REQUEST_URI]");
                }
            }
            //если данные были введены с ошибками
            elseif(isset($_SESSION['currentData'])){
                $currentData = $_SESSION['currentData'];
                $status = $_SESSION['status'];

                if(isset($_SESSION['errors'])){
                    $errors = $_SESSION['errors'];
                    unset($_SESSION['errors']);
                }
                unset($_SESSION['status']);
                unset($_SESSION['currentData']);
            }
        }
        require_once(ROOT . '/views/profile.html');
    }
}