<?php
	
	date_default_timezone_set( 'Europe/Moscow' );
	$year=date('Y');;
	$week_num = date('W');
	$host = "localhost";
	$user = "<user_name>";
	$password = "<password>";
	$database = "<db_name>";
	
	$api_id_sms = "<api_id_sms>";
	
	$days = array(
				'1'=>'Понедельник',
				'2'=>'Вторник',
				'3'=>'Среда',
				'4'=>'Четверг',
				'5'=>'Пятница',
				'6'=>'Суббота',
				'7'=>'Воскресенье'
				);
	
	$days_rod = array(
				'1'=>'понедельник',
				'2'=>'вторник',
				'3'=>'среду',
				'4'=>'четверг',
				'5'=>'пятницу',
				'6'=>'субботу',
				'7'=>'воскресенье'
				);
	
?>