<?php

$name_rider = 0;
$phone = 0;
$id_set_arr = array ();
$code_confirm = '00000';
$action = '';
$vowels = array("+", "(", ")", " ", "-");

$name_rider  = strip_tags(nl2br($_POST['name_rider']));
$phone  = strip_tags(nl2br(str_replace($vowels, "", $_POST['phone'])));
$id_set_arr  = explode("-",strip_tags(nl2br($_POST['id_set'])));
$action  = strip_tags(nl2br($_GET['action']));
if (!($id_set_arr) || !($action))
{
	echo "false";
	exit ();
}
else
{
	include ('../config.php');
	include ('functions_manage.php');
	$mysqli = new mysqli($host, $user, $password, $database);
	
	if ($mysqli->connect_error) 
	{
    	die("Connection failed: " . $mysqli->connect_error);
	} 

	if ($action =='confirm')
	{

		if (!($name_rider) || !($phone))
		{
			echo "false";
			exit ();
		}
		
		$s = 'SELECT t1.id
  			FROM week_set_times AS t1
 			WHERE t1.is_enabled = TRUE 
 			AND t1.day_num = '.$id_set_arr[0].' 
 			AND t1.set_time_num = '.$id_set_arr[1];
		
		
		$result = $mysqli->query($s);
		$row = $result->fetch_row();
		if ($row)
		{
				$s = 'DELETE FROM wake_table
      				WHERE     is_confirmed = FALSE
            		AND year = '.$year.'
            		AND week_num = '.$week_num.'
            		AND week_set_time_num = '.$row[0].'
            		AND set_num = '.$id_set_arr[2];

				$mysqli->query($s);
				unset($s);
				$query = "INSERT INTO wake_table (week_num, week_set_time_num, set_num, name_rider, phone, code, year, is_confirmed) VALUES (?,?,?,?,?,?,?,true)";
				$stmt = $mysqli->prepare($query);

				$stmt->bind_param("iiisssi", $val1, $val2, $val3, $val4, $val5, $val6, $val7);
				$val1 = $week_num;
				$val2 = $row[0];
				$val3 = $id_set_arr[2];
				$val4 = $name_rider;
				$val5 = $phone;
				$val6 = md5(md5($code_confirm));
				$val7 = $year;

				if ($stmt->execute())
				{
					echo "true";
				}
				else
				{
					echo "false";
				}
			$stmt->close();
			
		}
	}
	elseif ($action == 'remove')
	{
		$s = 'SELECT t1.id
  			FROM week_set_times AS t1
 			WHERE t1.is_enabled = TRUE 
 			AND t1.day_num = '.$id_set_arr[0].' 
 			AND t1.set_time_num = '.$id_set_arr[1];
		
		
		$result = $mysqli->query($s);
		$row = $result->fetch_row();
		if ($row)
		{
				$s = 'DELETE FROM wake_table
      				WHERE year = '.$year.'
            		AND week_num = '.$week_num.'
            		AND week_set_time_num = '.$row[0].'
            		AND set_num = '.$id_set_arr[2];

				$stmt = $mysqli->prepare($s);
				if ($stmt->execute())
				{
					echo "true";
				}
				else
				{
					echo "false";
				}
			$stmt->close();
			
		}
	}
	else
	{
		echo "false";
		exit();
	}
	$mysqli->close(); 

}
?>
