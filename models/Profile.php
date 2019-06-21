<?php

class Profile
{
    public static function addToBase(array $data){
        $db = Db::getConnection();
        $name = $data['name'];
        $second_name = $data['second_name'];
        $gender = $data['gender'];
        $group_name = $data['group_name'];
        $mail = $data['mail'];
        $points = $data['points'];
        $birth = $data['birth'];
        $location = $data['location'];


        $result = $db->query("INSERT INTO "
            ."student(name,second_name,gender,group_name,mail,points,birth,location) "
            ."VALUES ('$name','$second_name','$gender','$group_name','$mail','$points','$birth','$location')");

        //$db->errorCode();
        return $result;
    }


    public static function updateValues(array $newData, string $oldMail){
        $db = Db::getConnection();
        $name = $newData['name'];
        $second_name = $newData['second_name'];
        $gender = $newData['gender'];
        $group_name = $newData['group_name'];
        $mail = $newData['mail'];
        $points = $newData['points'];
        $birth = $newData['birth'];
        $location = $newData['location'];

        $result = $db->query("UPDATE student SET name='$name',second_name='$second_name',gender='$gender',"
        ."group_name='$group_name',mail='$mail',points='$points',birth='$birth',location='$location'"
        ."WHERE mail='$oldMail'");

        return $result;
    }
}