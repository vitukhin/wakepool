<?php

$name_rider = 0;
$phone = 0;
$id_set_arr = array ();
$code_confirm = 0;
$action = '';
$vowels = array("+", "(", ")", " ", "-");

$name_rider  = strip_tags(nl2br($_POST['name_rider']));
$phone  = strip_tags(nl2br(str_replace($vowels, "", $_POST['phone'])));
$id_set_arr  = explode("-",strip_tags(nl2br($_POST['id_set'])));
$code_confirm  = strip_tags(nl2br($_POST['code_confirm']));
$action  = strip_tags(nl2br($_GET['action']));

if (!($name_rider) || !($phone) || !($id_set_arr) || !($action))
{
	echo "false";
	exit ();
}
else
{
	include ('config.php');
	include ('functions.php');
	$mysqli = new mysqli($host, $user, $password, $database);
	
	if ($mysqli->connect_error) 
	{
    	die("Connection failed: " . $mysqli->connect_error);
	} 

	if ($action =='send' && !$code_confirm)
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
		
			$code_confirm =  generate_pass(5);
			$text = 'Ваш+код+подтверждения:+'.$code_confirm;
		
			$result_send = 0;
		
			$result_send=file_get_contents("http://sms.ru/sms/send?api_id=".$api_id_sms."&to=".$phone."&text=".$text);
		
			if ($result_send >= 200)
			{
				echo "false";
			}
			else
			{
				
		
				$s = 'DELETE FROM wake_table
      				WHERE     is_confirmed = FALSE
            		AND year = '.$year.'
            		AND week_num = '.$week_num.'
            		AND week_set_time_num = '.$row[0].'
            		AND set_num = '.$id_set_arr[2];

				$mysqli->query($s);
				unset($s);
				$query = "INSERT INTO wake_table (week_num, week_set_time_num, set_num, name_rider, phone, code, year) VALUES (?,?,?,?,?,?,?)";
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
	}
	elseif ($action == 'confirm')
	{
		if (!($code_confirm))
		{
			exit ();
		}
		else
		{
			$s = 'SELECT t1.code, t1.week_set_time_num FROM wake_table t1, week_set_times t2
      			WHERE     t1.is_confirmed = FALSE
            		AND t1.year = '.$year.'
            		AND t1.week_num = '.$week_num.'
            		AND t1.week_set_time_num = t2.id
            		AND t2.day_num = '.$id_set_arr[0].' 
 					AND t2.set_time_num = '.$id_set_arr[1].'
            		AND t1.set_num = '.$id_set_arr[2];
			$result = $mysqli->query($s);
			$row = $result->fetch_row();
			if ($row)
			{
				if ($row[0] == md5(md5($code_confirm)))
				{
					$s2 = 'UPDATE wake_table SET is_confirmed=1 
							WHERE year = '.$year.'
            				AND week_num = '.$week_num.'
            				AND week_set_time_num = '.$row[1].'
            				AND set_num = '.$id_set_arr[2];
					if ($mysqli->query($s2) === TRUE) 
					{
    					echo "true";
					} 
					else 
					{
    					echo "false";
					}
				}
				else
				{
					echo "false";
				}
			}
			else
			{
				echo "false";
			}
		}			
	}	
	else
	{
		exit();
	}
	$mysqli->close(); 

}
?>