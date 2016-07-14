<?php

class classReport {    

	public function generateHtmlTable($datas) 
	{
		$body = "
		<br>
		<table>
		<tr style='background:black;color:white'>
			<th>Stock</th>
			<th>Current</th>
			<th>BBP</th>
			<th>TP</th>  
			<th>ACTION</th>
		</tr>";
		
		foreach ($datas as $data) {
			$body .= "<tr style='font-family:verdana;font-size:11px;'>
			<td style='background:#C6DEFF;color:black;'>" . $data['symbol'] . "</td>
			<td style='background:#C6DEFF;color:black;'>" . $data['amount'] . "</td>
			<td style='background:#C6DEFF;color:black;'>" . $data['bbp'] . "</td>
			<td style='background:#C6DEFF;color:black;'>" . $data['tp'] . "</td>
			<td style='background:#C6DEFF;color:black;'>" . $data['action'] . "</td>
			</tr>
			";		
		} 
		
		$body .= "</table><br><br>Happy Trading!";		

		return $body;
		
	}	
	
}