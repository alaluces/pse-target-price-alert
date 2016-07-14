#!/usr/bin/php -q
<?php
/* 

20160715 

PSEi ALERT - Send daily reports and alerts you if one of your stocks 
already reached the target price 

*/

require 'class.phpmailer.php';
require 'classes.php';

$report  = new classReport($DBH);
$mail    = new PHPMailer();
$datenow = date("Y-m-d");

$data = array();  

// set your custom SAM table
$sams = array(
	array('symbol' => 'EDC', 'bbp' => '6.7', 'tp' => '8.24'),
	array('symbol' => 'IMII', 'bbp' => '7.8', 'tp' => '9.6'),
	array('symbol' => 'FPH', 'bbp' => '114', 'tp' => '138'),
	array('symbol' => 'SSI', 'bbp' => '4', 'tp' => '4.8'),
	array('symbol' => 'FLI', 'bbp' => '1.85', 'tp' => '2.23'),
	array('symbol' => 'FGEN', 'bbp' => '27', 'tp' => '31'),
	array('symbol' => 'MEG', 'bbp' => '4.59', 'tp' => '5.57'),
	array('symbol' => 'MPI', 'bbp' => '5.4', 'tp' => '6.56')
);

// get the stock quotes via api
$stocks = json_decode(shell_exec("curl -ss 'http://phisix-api4.appspot.com/stocks.json'"));

foreach ($stocks->stock as $stock) {
	foreach ($sams as $sam) {		
	    if ($sam['symbol'] == $stock->symbol) {
			$amt = $stock->price->amount;
			$bbp = $sam['bbp'];
			$tp  = $sam['tp'];

			if ($amt < $bbp) {
				$action = 'BUY';
			} else {
				$action = 'HOLD';
			}

			if ($amt > $tp) {
				$action = 'SELL';
			}
			array_push($data, array(
				'action' => $action,
				'symbol' => $stock->symbol, 
				'amount' => $amt, 
				'bbp' => $bbp, 
				'tp' => $tp
			));			
	    }
	}	
}


$mail->IsSMTP();                                  // telling the class to use SMTP 
#$mail->SMTPDebug  = 2;                           // enables SMTP debug information (for testing) 
$mail->SMTPAuth   = true;                         // enable SMTP authentication
$mail->SMTPSecure = 'tls';
$mail->Host       = 'smtp.gmail.com';             // sets the SMTP server
$mail->Port       = 587;                          // set the SMTP port for gmail
$mail->Username   = 'X@gmail.com'; // SMTP account username
$mail->Password   = 'X';
$mail->SetFrom('X@gmail.com', 'IT Report');
$mail->AddReplyTo('X@gmail.com','IT Report'); 

$body = $report->generateHtmlTable($data);

$mail->MsgHTML($body);
$mail->Subject = "PSE Stocks Alert $datenow";
$mail->AddAddress("X@yahoo.com", "Name");    

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!\n";
}       


?>
