<?php
	
function echo_day_table ($num_day = 1, $set_times = array (), $set_tables=array())
	{
		global $days;
		global $year;
		global $week_num;
		global $days_rod;
		
		$res = '</br>';
		$res .= '<table class="bordered">
    			<thead>
    				<tr>
        				<th colspan=2 style="-moz-border-radius: 6px 6px 0 0 !important;
        				-webkit-border-radius: 6px 6px 0 0 !important;
        				border-radius: 6px 6px 0 0 !important;">'.$days[$num_day].'</th>        
    				</tr>
    			</thead>';
    	foreach ($set_times as $k=>$val)
    	{
    		$day_str = '';
    		
    		$cur_day = date('d.m.Y', ($week_num-1) * 7 * 86400 + strtotime('1.1.' . $year) - date('w', strtotime('1.1.' . $year)) * 86400 + 86400*$num_day);
			
    		$day_str = $days_rod[$num_day].' '.$cur_day;
    		
    		$res .= '<tr rel="'.$val.'" day_str="'.$day_str.'">';
       					$res .= '<td style="width:40px;" class="time_set">';
       					$res .= $val;
       					$res .= '</td>        
        				<td style="width:100px;padding-bottom:2px;padding-top:5px;">';
        	
        	for ($i=1;$i<=4;$i++)
        	{
        		if (!isset($set_tables[$k][$i]))
        		{
        			$res .= '<a class="modalbox" rel="'.$num_day.'-'.$k.'-'.$i.'" href="#inline"><img id="img-'.$num_day.'-'.$k.'-'.$i.'" name="img-'.$num_day.'-'.$k.'-'.$i.'" src="pic/Box_Green.png" style="width:24px;height:24px;cursor:pointer;"/></a>';
        		}
        		else
        		{
        			$res .= '<img src="pic/Box_Red.png" style="width:24px;height:24px;"/>';
        		}
        	}
        $res .= '</td>
   			 </tr>';  
    	}
		$res .= '</table>';
		return $res;
		
	}
    
       
function generate_pass($number)  
      {  
        $arr = array('1','2','3','4','5','6',  
                     '7','8','9','0');  
        $pass = "";  
        for($i = 0; $i < $number; $i++)  
        {  
          $index = rand(0, count($arr) - 1);  
          $pass .= $arr[$index];  
        }  
        return $pass;  
      }
	
?>