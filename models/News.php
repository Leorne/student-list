<?php

class News{

	public static function getNewsItemById($id){

		$db = Db::getConnection();
		$result = $db->query('SELECT * FROM news WHERE id='.$id);

		$newsItem = $result->fetch(PDO::FETCH_NAMED);

		return $newsItem;
	}


	public static function getNewsList(){

		$db = Db::getConnection();
		$newsList = array();

		$result = $db->query('SELECT id,title,date,short_content '
							.'FROM news '
							.'ORDER BY date DESC '
							.'LIMIT 10');

		$i = 0;
		while($row = $result->fetch()){
			$newsList[$i]['id'] = $row['id'];
			$newsList[$i]['title'] = $row['title'];
			$newsList[$i]['date'] = $row['date'];
			$newsList[$i]['short_content'] = $row['short_content'];
			$i++;
		}
		return $newsList;
	}
}