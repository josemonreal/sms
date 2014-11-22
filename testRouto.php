<?php
// include RoutoSMS class
include("RoutoTelecomSMS.php");
session_start();
echo print_r($_POST);
echo "<br>";
echo print_r($_SESSION);

/*
'host' => 'www.syspaweb.com',
		'login' => 'syspaweb_jose',
		'password' => 'joma1987',
		'database' => 'syspaweb_clasi',
*/

$conn=mysql_connect("www.syspaweb.com","syspaweb_jose","joma1987");
$seldb=mysql_select_db("syspaweb_sms",$conn);
$user_Id=1;


$num=mysql_query("SELECT total_sms FROM messages WHERE user_id=$userId");
$num_array=mysql_fetch_array($num);
$num_sms=$num_array[0];
echo $num_sms."*******";

if($num_sms > 0) {

	$new_num=$num_sms-1;

	$qry=mysql_query("UPDATE  messages SET total_sms=$new_num WHERE user_id=$userId");
	//=mysql_fetch_array($qry);
}

// creating object

/*
$sms = new RoutoTelecomSMS;
// setting login parameters
$sms->SetUser("1183531");
$sms->SetPass("z5hy5449n");
$sms->SetOwnNum(3311945330); // optional
$sms->SetType("SMS"); // optional
// get values entered from FORM
$sms->SetNumber(523921069013);
$sms->SetMessage("This is the test message 2, de new bussiness by joma");
// send SMS and print result
$smsresult = $sms->Send();
echo '<pre>'.print_r( $smsresult)'</pre>';
*/
?>