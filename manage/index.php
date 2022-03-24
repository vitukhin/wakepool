<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>Wakepool table manage</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="all" href="../css/style.css">
 		<link rel="stylesheet" type="text/css" media="all" href="../fancybox/jquery.fancybox.css">
  		<link rel="stylesheet" href="../css/header.css" type="text/css">
  		<script type="text/javascript" src="../js/jquery.min.js"></script>
  		<script type="text/javascript" src="../js/jquery.maskedinput.min.js"></script>
  		<script type="text/javascript" src="../fancybox/jquery.fancybox.js"></script>
  		<script type="text/javascript" src="js/js.js"></script>	
	</head>

<?php

	$set_times = array();
	$set_tables = array();
		
	include ('../config.php');
	include ('functions_manage.php');
	
	?>

	<body>	
		<div id="content"> 

		<?php	
			
			$first_day = date('d.m.Y', ($week_num-1) * 7 * 86400 + strtotime('1.1.' . $year) - date('w', strtotime('1.1.' . $year)) * 86400 + 86400);
			$last_day = date('d.m.Y', $week_num * 7 * 86400 + strtotime('1.1.' . $year) - date('w', strtotime('1.1.' . $year)) * 86400);
			
			$s = 'SELECT t1.day_num, t1.set_time_num, t2.time_begin
    				FROM week_set_times AS t1, set_times AS t2
   					WHERE t1.set_time_num = t2.id AND t1.is_enabled = TRUE
					ORDER BY t1.day_num, t2.id';
			
			$s_table = 'SELECT t2.day_num,
       					t2.set_time_num,
       					t1.set_num,
       					t1.name_rider,
       					t1.phone
  						FROM wake_table AS t1, week_set_times AS t2
 						WHERE     t1.week_set_time_num = t2.id
      				 	AND t1.year = '.$year.'
       					AND t1.week_num = '.$week_num.'
       					AND t2.is_enabled = TRUE
       					AND t1.is_confirmed = TRUE';
			
			$mysqli = new mysqli($host, $user, $password, $database);
			$result = $mysqli->query($s);
			$res_table = $mysqli->query($s_table);
	
			if (mysqli_connect_errno()) 
			{
    			printf("Connect failed: %s\n", mysqli_connect_error());
    			exit();
			}

			if ($result)
			{
				while ($obj = $result->fetch_object())
				{
					$set_times[$obj->day_num][$obj->set_time_num] = $obj->time_begin;			
				}
			}
			
			if ($res_table)
			{
				while ($obj_table = $res_table->fetch_object())
				{
					$set_tables[$obj_table->day_num][$obj_table->set_time_num][$obj_table->set_num] = $obj_table->name_rider.', '.phone_number($obj_table->phone);			
				}
			}
			
			?>
			
			<table>
				<tr>
					<td colspan=3>
						<h1 style="text-align:center;margin:0px;">Расписание занятий c <?php echo $first_day; ?> по <?php echo $last_day; ?>
						</br> (редактирование расписания)</h1>
					</td>
				</tr>
				
				<tr>
					<td colspan=3>
						<h2 style="text-align:left;font-size:16px;margin:0px;">1. Нажмите на сет для записи или отмены записи
						</br>
						2. При наведении курсора на занятый сет отображается имя и телефон записанного</h2>
					</td>
				</tr>
				
				<tr>
					<td style="vertical-align:top;">
						<?php
						
						for ($i=1;$i<=3;$i++)
						{
							echo echo_day_table($i,$set_times[$i],isset($set_tables[$i])?$set_tables[$i]:array());
						}
						?>
					</td>
					<td style="vertical-align:top;padding-left:25px;">
						<?php
						for ($i=4;$i<=6;$i++)
						{
							
							echo echo_day_table($i,$set_times[$i],isset($set_tables[$i])?$set_tables[$i]:array());
						}
						?>
					</td>
					<td style="vertical-align:top;padding-left:25px;">
						<?php
							echo echo_day_table(7,$set_times[7],isset($set_tables[7])?$set_tables[7]:array());
						?>
					</td>
				</tr>
			</table>
			
		<?php	
			$result->close();
			$res_table->close();
			$mysqli->close();
			unset($obj);
			unset($obj_table);
			unset($s);
			unset($s_table);
			unset($set_times);
			unset($set_tables);
		?>
		</div>
		
<div id="inline">
	<h2 id="title_zap" name="title_zap">Запись на лебедку</h2>

	<form id="contact" name="contact" action="#"  onsubmit="return false;" method="post">
	<div id="contact_params" name="contact_params">
		<input type="hidden" id="id_set" name="id_set" value="">
		<label for="name_rider">Имя:</label>
		<input type="text" id="name_rider" name="name_rider" class="txt" onfocus="$('#name_rider').removeClass('error')">
		<br />
		<label for="phone">Телефон:</label>
		<input type="text" id="phone" name="phone" class="txt" onfocus="$('#phone').removeClass('error')">
		<div style="text-align:center;">
			<button id="send">Записать</button>
		</div>
	</div>
	
	<div id="remove_params" name="remove_params" style="text-align:center;">
			<button id="remove">Удалить запись</button>
	</div>
	
	</form>
</div>
		
	</body>
</html>